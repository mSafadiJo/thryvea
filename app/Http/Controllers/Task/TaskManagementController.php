<?php

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task_Management;
use Illuminate\Support\Facades\DB;

class TaskManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index()
    {
        $tasks = DB::table('task_management')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('Admin.Tasks.list_of_tasks')
            ->with('tasks', $tasks);
    }

    public function showFormAdd(){
        return view('Admin.Tasks.add_tasks');
    }


    public function TasksStore(Request $request){
        $this->validate($request, [
            'tasktname' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'buyername' => ['required', 'string', 'max:255'],
            'status',
            'signedto'
        ]);

        $taskName = $request['tasktname'];
        $priority = $request['priority'];
        $description=$request['description'];
        $buyername = $request['buyername'];
        $status = $request['status'];

        if( empty($request['signedto']) ){
            $signedto = "[]";
        } else {
            $signedto = json_encode($request['signedto']);
        }

        $Task_Management = new Task_Management();
        $Task_Management->task_name = $taskName;
        $Task_Management->description = $description;
        $Task_Management->status = $status ;
        $Task_Management->priority = $priority;
        $Task_Management->assign_from = $buyername;
        $Task_Management->signed_to = $signedto ;
        $Task_Management->save();

        return redirect('Admin/list_of_tasks');
    }

    public function edit($id){
        $task = Task_Management::find($id);

        return view('Admin.Tasks.edit', compact('task'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'tasktname' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'buyername' => ['required', 'string', 'max:255'],
            'status',
            'signedto'
        ]);

        $taskName = $request['tasktname'];
        $priority = $request['priority'];
        $description=$request['description'];
        $buyername = $request['buyername'];
        $status = $request['status'];
        $signedto = json_encode($request['signedto']);


        $Task_Management = Task_Management::find($id);
        $Task_Management->task_name = $taskName;
        $Task_Management->description = $description;
        $Task_Management->status = $status ;
        $Task_Management->priority = $priority;
        $Task_Management->assign_from = $buyername;
        $Task_Management->signed_to = $signedto ;
        $Task_Management->save();

        return redirect('Admin/list_of_tasks');
    }

    public function deleteTask($id){

        DB::table('task_management')->where('id', '=', $id)->delete();

        echo "Delete successfully";
        exit;
    }

    public function TaskStatus(Request $request){
        $id = $request['id'];
        $status = $request['status'];


        $change = DB::table('task_management')->where('id', $id)
            ->update(['status'=> $status]);
        return response()->json($change, 200);
    }

    public function TaskSignedTo(Request $request){
        $id = $request['id'];
        $SignedTo = json_encode($request['SignedTo']);
        $change = DB::table('task_management')->where('id', $id)
            ->update(['signed_to'=> $SignedTo]);
        return response()->json($change, 200);
    }


}
