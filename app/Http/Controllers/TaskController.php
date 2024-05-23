<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        Task::create([
            'name' => $request->name,
            'end_date' => $request->end_date,
        ]);
        return response('', 201);
    }

    public function show()
    {

    }

}
