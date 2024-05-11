<?php

namespace App\Http\Controllers;

use App\Models\Commentary;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentaryController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $comm = Commentary::create([
            'user_id' => $request->user()->id,
            'commentary' => $request->commentary,
        ]);
        $task->commentaries()->attach($comm);
    }

    public function show()
    {

    }
}
