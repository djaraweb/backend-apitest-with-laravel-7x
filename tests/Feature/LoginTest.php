<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as CodeResponse;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
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
    }


    public function test_register_user_when_fields_is_not_present()
    {
        $response = $this->post(route('register'),[]);        
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonPath('message.errors.name',['The name field is required.'])
                ->assertJsonPath('message.errors.email',['The email field is required.'])
                ->assertJsonPath('message.errors.password',['The password field is required.']);
    }

    public function test_register_user_when_field_name_is_not_present()
    {
        $response = $this->post(route('register'),[
                            'email'=>'djara@gmail.com',
                            'password'=>'secret'
                        ]);
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonPath('message.errors.name',['The name field is required.']);
    }

    public function test_register_user_when_field_email_is_not_valid()
    {
        $response = $this->post(route('register'),[
                            'name'=>'Robert Palacios Lopez',
                            'email'=>'djaragmail.com',
                            'password'=>'secret'
                        ]);
        //$response->dump();
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonPath('message.errors.email',['The email must be a valid email address.']);
    }

    public function test_register_user_when_fields_is_valid()
    {
        $response = $this->post(route('register'),[
                            'name'=>'Deyvi Jara Garcia',
                            'email'=>'djara@testunit.com',
                            'password'=>'secret'
                        ]);
        $response->assertOk()
                ->assertJsonPath('body.message','Usuario Creado Correctamente')
                ->assertJsonPath('body.user.name','Deyvi Jara Garcia')
                ->assertJsonPath('body.user.email','djara@testunit.com');
    }

    public function test_login_when_fields_is_not_present()
    {
        $response = $this->post(route('login'),[]);
        $response->assertStatus(CodeResponse::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonPath('message.errors.email',['The email field is required.'])
                ->assertJsonPath('message.errors.password',['The password field is required.']);
    }

    public function test_login_when_user_not_exists()
    {
        $response = $this->post(route('login'),[
            'email'=>'djara@testunit.com',
            'password'=>'secret'
        ]);
        $response->assertStatus(CodeResponse::HTTP_UNAUTHORIZED)
                ->assertJsonPath('message','Usuario, clave son incorrectos')
                ->assertJsonPath('code',CodeResponse::HTTP_UNAUTHORIZED);
    }

    public function test_login_when_user_exists()
    {
        $this->withoutExceptionHandling();
        // registramos el usuario
        $parameter = ['name'=>'Deyvi Jara', 'email'=>'djara@testunit.com', 'password' => 'secretDemo'];
        $response = $this->post(route('register'),$parameter);
        $response = $this->post(route('login'),[
            'email'=>$parameter['email'],
            'password'=>$parameter['password']
        ]);
        // Validamos el usuario
        $response->assertOk()
                ->assertJsonPath('body.message','Usuario, logueado correctamente')
                ->assertJsonPath('body.expires_at', Carbon::now()->addMinutes(60)->toDateTimeString())
                ->assertJsonPath('code',CodeResponse::HTTP_OK);
    }



}
