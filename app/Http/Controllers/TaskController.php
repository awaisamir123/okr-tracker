<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Status;
use App\Models\Role;
use App\Models\Task;
use Session;

class TaskController extends Controller
{
    public function index(request $request)
    {
        if(isset($_GET['role'])){
            if($_GET['role'] != ''){
                session(['currentRole' => $_GET['role']]);
            }else{
                $request->session()->forget('currentRole');
            }
        }
        $statuses = Status::all();
        $roles = Role::all();

        return view('index', compact('statuses','roles'));
    }
    public function store(request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = '1';

        if($task->save()){
            return response()->json([
                'status' => '1',
                'msg' => 'Task Created.',
                'content' => $task,
            ]);
        }else{
            return response()->json([
                'status' => '0',
                'msg' => 'Something went wrong. Try again',
                'content' => null,
            ]);
        }
    }
    public function updateStatus()
    {
        $task_id = (isset($_GET['task_id'])?$_GET['task_id']:'');
        $status_id = (isset($_GET['status_id'])?$_GET['status_id']:'');

        if($task_id == '' || $status_id == ''){
            return response()->json([
                'status' => '0',
                'msg' => 'Something went wrong. Try again',
                'content' => null,
            ]);
        }

        $task = Task::find($task_id);
        if(!$task){
            return response()->json([
                'status' => '0',
                'msg' => 'Task not found',
                'content' => null,
            ]);
        }else{
            $task->status_id = $status_id;
            $task->save();
            return response()->json([
                'status' => '1',
                'msg' => 'Task updated',
                'content' => $task,
            ]);
        }
    }
}
