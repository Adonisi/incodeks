<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tasks;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $tasks = Tasks::with('users')->get();
        // return $tasks;

        $is_admin = Auth::user();
        //
        $taskUsers = Tasks::with('users')->get();
        if($is_admin->role_id == 1){

            $taskUsers->sortBy('due_date')->map(function ($task) {
                return [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'task_description' => $task->description,
                    'task_due_date' => $task->due_date,
                    'task_status' => $task->status->name,
                    'users' => $task->users->map(function ($user) {
                        return [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                        ];
                    }),
                ];
            });
        }
        else{

            $taskUsers = $taskUsers->filter(function ($task) {
                return  $task->users->where('id', Auth::user()->id)->count() > 0;
            })->sortBy('due_date')->map(function ($task) {
                return [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'task_description' => $task->description,
                    'task_due_date' => $task->due_date,
                    'task_status' => $task->status->name,
                ];
            });
        }

        return $taskUsers;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date|after:yesterday',
            'status_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $task = Tasks::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status_id' => $request->status_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'task' => $task,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task = Tasks::find($id);
        return response()->json([
            'status' => 'success',
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date|after:yesterday',
            'status_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $task = Tasks::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status_id = $request->status_id;
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = Tasks::find($id);
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
            'task' => $task,
        ]);
    }

    public function assign_task(Request $request,$id){

        $tasks_id = $request->tasks_id;
        $tasks_array = explode(',',$tasks_id);

        $user = User::find($id);
        $user->tasks()->syncWithoutDetaching($tasks_array);

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks atached successfully',
        ]);
    }

    public function deassign_task(Request $request,$id){

        $tasks_id = $request->tasks_id;
        $tasks_array = explode(',',$tasks_id);

        $user = User::find($id);
        $user->tasks()->detach($tasks_array);

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks detached successfully',
        ]);
    }

    public function search($keyword){

        $status_id = Status::where('name',$keyword)->first();

        $task = Tasks::where('title', 'like', '%'.$keyword.'%')
                    ->OrWhere('status_id', $status_id->id)->get(); 
        return $task;
    }
}
