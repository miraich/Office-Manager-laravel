<?php

namespace App\Http\Controllers;

use App\Enums\Statuses;
use App\Http\Requests\UpdateTaskFormRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
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

    public function update(UpdateTaskFormRequest $request, Project $project, Task $task)
    {
        switch ($request->status_id) {
            case Statuses::NOT_STARTED->value:
                $task->status_id = Statuses::NOT_STARTED->value;
                $task->save();
                $project->status_id = $project->setCurrentStatus();
                $project->save();
                return response('', 204);
            case Statuses::IN_PROGRESS->value:
                $task->status_id = Statuses::IN_PROGRESS->value;
                $task->save();
                $project->status_id = $project->setCurrentStatus();
                $project->save();
                return response('', 204);
            case Statuses::FINISHED->value:
                $task->status_id = Statuses::FINISHED->value;
                $task->save();
                $project->status_id = $project->setCurrentStatus();
                $project->save();
                return response('', 204);
        }
        return response('bad request', 400);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response('deleted', 200);
    }
    public function show()
    {

    }
}
