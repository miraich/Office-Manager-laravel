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

    public function deleteFromGroup(Group $group, User $user)
    {
        if ($group->exists() && $user->exists()) {
            DB::table('user_group')
                ->where('user_id', $user->id)
                ->where('group_id', $group->id)
                ->delete();
            return response('deleted');
        }
        return response('user or group not found', 404);
    }
}
