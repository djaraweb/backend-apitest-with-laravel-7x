<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as CodeResponse;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;
use App\Task;

class TasksTest extends TestCase
{
    use RefreshDatabase ;
    private $accessToken;

    protected function setUp(): void
    {
        parent::setUp();
        // Create Passport - Personal Access Token for Unit Test 
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        // registramos el usuario
        $parameter = ['name'=>'Deyvi Jara', 'email'=>'djara@testunit.com', 'password' => 'secretDemo'];
        $response = $this->post(route('register'),$parameter);
        // Realizamos el login de usuario creado
        $response = $this->post(route('login'),[
            'email'=>$parameter['email'],
            'password'=>$parameter['password']
        ]);  
        // Seteamos el access_token
        $this->accessToken = $response->getData()->body->access_token;
        $response->assertOk()
                ->assertJsonPath('body.message','Usuario, logueado correctamente')
                ->assertJsonPath('code',CodeResponse::HTTP_OK);

        
    }

    public function test_list_tasks_when_no_records_exist()
    {
        //Disable exception handling
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
                            'Authorization' => "Bearer $this->accessToken"
                        ])
                         ->get(route('tasks.index'));        
        $response->assertOk()
                ->assertJsonPath('body.tasks.data', [])
                ->assertJsonPath('body.tasks.current_page', 1)
                ->assertJsonPath('body.tasks.total', 0)
                ->assertJsonPath('code', CodeResponse::HTTP_OK);
    }

    public function test_list_tasks_when_records_exist()
    {
        //Disable exception handling
        $this->withoutExceptionHandling();
        $task = factory(Task::class, 1)->create();

        $response = $this->withHeaders([
                'Authorization' => "Bearer $this->accessToken"
            ])->get(route('tasks.index'));       
        //$data = $response->getData();
        $response->assertOk()
                 ->assertJsonPath('body.tasks.data', [
                        [
                            'id'=>$task[0]->id,
                            'title'=>$task[0]->title,
                            'completed'=>$task[0]->completed
                        ]
                     ])
                 ->assertJsonPath('body.tasks.current_page', 1)
                 ->assertJsonPath('body.tasks.total', 1)
                 ->assertJsonPath('code', CodeResponse::HTTP_OK);
    }

    public function test_list_tasks_when_filter_no_records_exist()
    {
        //Disable exception handling
        $this->withoutExceptionHandling();
        factory(Task::class, 5)->create();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->get(route('tasks.index', ['field' => 'title','valuefield' => 'xx**not-record**xx']));
        //$data = $response->getData(); 
        //$response->dump();
        $response->assertOk()
                ->assertJsonPath('body.tasks.data', [])
                ->assertJsonPath('body.tasks.current_page', 1)
                ->assertJsonPath('body.tasks.total', 0)
                ->assertJsonPath('code', CodeResponse::HTTP_OK); 
    }

    public function test_list_tasks_when_filter_records_exist()
    {
        //Disable exception handling
        $this->withoutExceptionHandling();
        $task = factory(Task::class, 5)->create();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->get(route('tasks.index', ['field' => 'title','valuefield' => $task[0]->title]));
        //$data = $response->getData(); 
        $response->assertOk()
                 ->assertJsonPath('body.tasks.data', [
                        [
                            'id'=>$task[0]->id,
                            'title'=>$task[0]->title,
                            'completed'=>$task[0]->completed
                        ]
                     ])
                 ->assertJsonPath('body.tasks.current_page', 1)
                 ->assertJsonPath('body.tasks.total', 1)
                 ->assertJsonPath('code', CodeResponse::HTTP_OK);
    }

    // Create
    public function test_create_task_when_fields_is_not_present(){
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->post(route('tasks.store'),[]);                
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonPath('message.errors.title',['The title field is required.'])
                 ->assertJsonPath('message.errors.completed',['The completed field is required.']);
    }

    public function test_create_task_when_field_title_is_not_present(){
         $parameters = ['completed'=>1];
         $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->post(route('tasks.store'),$parameters);        
         $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                  ->assertJsonPath('message.errors.title',['The title field is required.']);
     }

    public function test_create_task_when_field_completed_is_not_present(){
        $parameters = ['title'=>'New task, Learning PHPunit Test'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->post(route('tasks.store'),$parameters);        
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonPath('message.errors.completed',['The completed field is required.']);
    }

    public function test_create_task_when_is_present(){
        //$this->withoutExceptionHandling();
        $parameters = ['title'=> 'Lorem ipsum dolor sit',
                        'completed' => 1];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->post(route('tasks.store'),$parameters);       
        $response->assertOk()
                 ->assertJsonPath('code',CodeResponse::HTTP_OK)
                 ->assertJsonPath('body.task.title',$parameters['title'])
                 ->assertJsonPath('body.task.completed',1);
    }

    // Update
    public function test_update_task_when_field_not_exist(){
        $taskId = 1;
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->put(route('tasks.update',$taskId),[]);                
        $response->assertNotFound()
                 ->assertJsonPath('message','No existe ninguna instancia de [task] con el Id especificado')
                 ->assertJsonPath('code',CodeResponse::HTTP_NOT_FOUND);
    }

    public function test_update_task_when_fields_is_not_present(){   
        $task = factory(Task::class, 1)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->put(route('tasks.update',$task[0]->id),[]);                
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonPath('message.errors.title',['The title field is required.'])
                 ->assertJsonPath('message.errors.completed',['The completed field is required.']);
    }

    public function test_update_task_when_field_title_is_not_present(){
        $task = factory(Task::class, 1)->create();        
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->put(route('tasks.update',$task[0]->id),[
            'completed' => 0
        ]);                
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonPath('message.errors.title',['The title field is required.']);
    }

    public function test_update_task_when_field_completed_is_not_present(){
        $task = factory(Task::class, 1)->create();         
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->put(route('tasks.update',$task[0]->id),[
            'title' => 'new task updated'
        ]);                
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                 ->assertJsonPath('message.errors.completed',['The completed field is required.']);
    }

    public function test_update_task_when_fields_is_present(){
        $task = factory(Task::class, 1)->create();        
        $parameters = [
            'title' => 'new task updated',
            'completed' => 0
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->put(route('tasks.update',$task[0]->id),$parameters);                
        $response->assertOk()
                 ->assertJsonPath('code',codeResponse::HTTP_OK)
                 ->assertJsonPath('body.task.title',$parameters['title'])
                 ->assertJsonPath('body.task.completed',$parameters['completed']);                
    }

    // Destroy
    public function test_destroy_task_when_field_id_is_not_exist(){
        $taskId = 1;
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->delete(route('tasks.destroy',$taskId));                
        $response->assertNotFound()
                 ->assertJsonPath('message','No existe ninguna instancia de [task] con el Id especificado')
                 ->assertJsonPath('code',CodeResponse::HTTP_NOT_FOUND);
    }

    public function test_destroy_task_when_field_id_is_present(){
        $task = factory(Task::class, 1)->create();               
        $response = $this->withHeaders([
            'Authorization' => "Bearer $this->accessToken"
        ])->delete(route('tasks.destroy',$task[0]->id));                
        $response->assertOk()
                 ->assertJsonPath('code',CodeResponse::HTTP_OK)
                 ->assertJsonPath('body','Item Deleted');                
    }


}
