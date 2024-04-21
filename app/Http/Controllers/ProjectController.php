<?php

namespace App\Http\Controllers;

use App\Filters\ProjectsFilter;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    
    public function index(Request $request)
    {
        $filter = new ProjectsFilter();
        $queryItems = $filter->transform($request);
        $categories = Project::where([
            ...$queryItems,
            'user_id' => $request->user()->id
        ])->get();
        return new ProjectCollection($categories);
    }


    public function store(StoreProjectRequest $request)
    {
        return new ProjectResource(Project::create([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update([
            ...$request->all(),
            'user_id' => $request->user()->id
        ]);
    }

    public function destroy(Request $request, Project $project)
    {
        if($request->user()->cannot('delete', [$project, ProjectPolicy::class])){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $project->delete();
    }

}
