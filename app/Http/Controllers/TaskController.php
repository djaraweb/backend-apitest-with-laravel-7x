<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends ApiController
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $var_campo =  $request->get('field');
        $var_value = $request->get('valuefield');

        if ($var_value){
            $tasks = Task::where($var_campo, 'like', '%'. $var_value . '%')
                            ->orderBy('id','desc')
                            ->paginate();
        }else {
            $tasks = Task::orderBy('id','desc')->paginate();
        }

        return $this->responseToCollection(compact('tasks'));

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
                'title' => 'required|string',
                'completed' => 'required|boolean'
                ]);

        $task = Task::create($data);
        return $this->responseToSuccess(compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'completed' => 'required|boolean'
            ]);

        $task->update($data);

        return $this->responseToSuccess(compact('task'));
    }

    public function updateAll(Request $request)
    {
        $data = $request->validate([
            'completed' => 'required|boolean'
            ]);

        Task::query()->update($data);

        return $this->responseToSuccess('Update All Tasks');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return $this->responseToSuccess('Item Deleted');
    }

    public function destroyCompleted(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array'
            ]);

        Task::destroy($request->tasks);
        return $this->responseToSuccess('Items Deleted');
    }
}
