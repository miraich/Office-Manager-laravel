<?php

namespace App\Http\Controllers;

use App\Enums\Statuses;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        if ($user->projects()->exists() || $user->groups()->exists()) {

            $userProjects = $user->projects;

            $groupUserIds = $user->groups->flatMap(function ($group) { //оч полезный метод.........
                return $group->users->pluck('id');
            })->unique()->diff([$user->id]);

            $groupProjects = Project::whereIn('owner_id', $groupUserIds)->get();

            $allProjects = $userProjects->concat($groupProjects);

            $allProjects->flatMap(function ($project) {
                $project->status_id = $project->setCurrentStatus();
                $project->save();
            });

            return response()->json($allProjects, 200);
        }
        return response()->noContent();
    }

    public function store(Request $request)
    {
        $path = null;
        if ($request->file('file')) {
            $path = $request->file('file')->store('documentation');
        }

        $proj = Project::create([
            'status_id' => Statuses::NOT_STARTED->value,
            'owner_id' => $request->user()->id,
            'name' => $request->title,
            'description' => $request->projectDescription,
            'budget' => $request->budget,
            'end_date' => $request->date,
            'documentation' => $path,
        ]);
        return response('', 201);
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);
        return response()->json($project->makeVisible(['budget', 'end_date', 'tasks']), 200);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }

    public function download(Project $project)
    {
        $path = $project->documentation;
//        response()->download(storage_path() . "/app/" . $path,'file');

        if (!empty($path) && Storage::disk('local')->exists($path)) {
            return Storage::download($path);
        }
        return response()->json(['error' => 'File not found.'], 404);
    }
}
