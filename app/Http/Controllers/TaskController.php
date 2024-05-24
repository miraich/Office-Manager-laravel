<?php

namespace App\Http\Controllers;

use App\Enums\Statuses;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request, Project $project)
    {
        $project->tasks()->create([
            'status_id' => Statuses::NOT_STARTED->value,
            'name' => $request->name,
            'description' => $request->taskDescription,
            'end_date' => $request->end_date,
        ]);

        return response('', 201);
    }

    public function update(Request $request, Task $task)
    {

    }

    public function show()
    {

    }

}
