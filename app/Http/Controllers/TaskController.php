<?php

namespace App\Http\Controllers;

use App\Filters\TasksFilter;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = new TasksFilter();
        $queryItems = $filter->transform($request);
        $tasks = Task::where([
            ...$queryItems,
            'user_id' => $request->user()->id
        ])->with('project')->get();

        return [
            'data' => new TaskCollection($tasks),
            'status' => 200,
            'message' => 'Datos obtenidos correctamente'
        ];
    }

    public function store(StoreTaskRequest $request)
    {
        $task = new TaskResource(Task::create([
            ...$request->all(),
            'date' => date('Y-m-d'),
            'seconds' => 0,
            'user_id' => $request->user()->id,
            'started_at' => Carbon::parse($request->started_at)->toDateTimeString()
        ])->with('project'));

        return [
            'data' => $task,
            'status' => 201,
            'message' => 'Recurso creado con éxito'
        ];

    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);

        return [
            'data' => $task,
            'status' => 200,
            'message' => 'Recurso actualizado con éxito'
        ];
    }

    public function destroy(Request $request, Task $task)
    {
        if($request->user()->cannot('delete', [$task, TaskPolicy::class])){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();


        return [
            'data' => [],
            'status' => 200,
            'message' => 'Recurso eliminado con éxito'
        ];
    }
}
