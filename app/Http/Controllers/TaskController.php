<?php

namespace App\Http\Controllers;

use App\Filters\TasksFilter;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = new TasksFilter();
        $queryItems = $filter->transform($request);
        $categories = Task::where([
            ...$queryItems,
            'user_id' => $request->user()->id
        ])->get();
        return new TaskCollection($categories);
    }

    public function store(StoreTaskRequest $request)
    {
        return new TaskResource(Task::create([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);
    }

    public function destroy(Request $request, Task $task)
    {
        if($request->user()->cannot('delete', [$task, TaskPolicy::class])){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();
    }
}
