<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function currentUser(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }


}
