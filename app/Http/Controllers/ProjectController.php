<?php

namespace App\Http\Controllers;

use App\Enums\Statuses;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    function index()
    {
        $user = auth()->user();

        if ($user->projects()->exists() || $user->groups()->exists()) {

            $userProjects = $user->projects;
            $groupIds = $user->groups->pluck('id');

            // Получаем проекты групп, где user_id отличается от owner_id пользователя
            $groupProjects = Project::whereIn('owner_id', function ($query) use ($groupIds) {
                $query->select('owner_id')
                    ->from('groups')
                    ->whereIn('id', $groupIds);
            })->where('owner_id', '!=', $user->id)->get();

            // Преобразуем коллекцию Eloquent-моделей в формат, содержащий только нужные поля
            $groupProjects = $groupProjects->map(function ($project) {
                return $project->only(['id', 'status_id', 'name', 'description', 'documentation']);
            });


            return response()->json([
                'userProjects' => $userProjects,
                'groupProjects' => $groupProjects,
            ], 200);
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

        if (!empty($path) && Storage::disk('local')->exists($path)) {
            return Storage::download($path);
        }
        return response()->json(['error' => 'File not found.'], 404);
    }
}
