<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $users = User::whereHas('task', function ($query) use ($id) {
            $query->where('updated_at', '>', Carbon::now()->subDays(8))
                ->whereHas('project', function ($q) use ($id) {
                    $q->whereId($id);
                });
        })->paginate(10);
        foreach ($users as $key => $user) {
            $data[$key] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            foreach ($user->task as $k => $task) {
                $data[$key]['task'][$k] = [
                    'project_id' => $task->project->id,
                    'project_name' => $task->project->name,
                    'task_id' => $task->id,
                    'header' => $task->header,
                    'body' => $task->body,
                ];
            }
        }
        if(!isset($data)){
            $data = ['messages' => 'not found'];
            return response()->json($data, 500);
        }
        return response()->json($data, 200);

    }


    public function store(Request $request)
    {

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }




}
