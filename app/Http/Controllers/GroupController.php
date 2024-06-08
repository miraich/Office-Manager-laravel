<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Notifications\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $groupsCreatedByUser = $user->groupsCreated;

        $groupsUserInvited = $user->groups->filter(function ($group) use ($user) {
            return $group->owner_id != $user->id;
        })->values();



        return response()->json([
            'groupsCreatedByUser' => $groupsCreatedByUser,
            'groupsUserInvited' => $groupsUserInvited,
        ], 200);
    }

    public function store(Request $request)
    {
        $code = Str::random(10);
        Group::create([
            'owner_id' => $request->user()->id,
            'name' => $request->name,
            'invitation_code' => $code,
        ]);
        return response('created', 201);
    }

    public function invite(Request $request)
    {
        $group = Group::find($request->group_id);
        if ($group->exists()) {
            Notification::route('mail', $request->email)->notify(new InvitationMail($group->invitation_code,
                $request->user(), $group));
        }
        return response('', 200);
    }

    public function confirmUser(Request $request)
    {
        $group = Group::where('invitation_code', $request->link);
        if ($group->exists()) {
            if (auth()->user()->groups->contains($group->first()->id)) {
                return response('User already in group', 400);
            }
            $request->user()->groups()->attach($group->get());
            return response('Invitation confirmed', 200);
        }
        return response('Group does not exist', 404);
    }
}
