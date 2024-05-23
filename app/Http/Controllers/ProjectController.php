<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        if ($user->projects()->exists() || $user->groups()->exists()) {

            $user_projects = $user->projects;

            $groupUserIds = $user->groups->flatMap(function ($group) { //оч полезный метод.........
                return $group->users->pluck('id');
            })->unique()->diff([$user->id]);

            $groupProjects = Project::whereIn('owner_id', $groupUserIds)->get();

            $allProjects = $user_projects->concat($groupProjects);

            $allProjects->flatMap(function ($project) {
                $project->status_id = $project->setCurrentStatus();
                $project->save();
            });

            return response()->json($allProjects, 200);
        }
        return response()->json([], 204);
    }

    public function store(Request $request)
    {
        Project::create([
            'name' => $request->title,
            'description' =>$request->projectDescription,
            'end_date' => $request->date,
        ]);
        return response()->json([], 201);
    }

    public function show(Project $project)
    {
        return response()->json($project, 200);
    }
}
