<?php

namespace App\Http\Controllers;

use App\Enums\Subscriptions;
use App\Models\Group;
use App\Models\User;
use App\Notifications\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $groupsCreatedByUser = $user->groupsCreated->map(function ($group) use ($user) {
            $groupArray = $group->toArray();
            $groupArray['creator'] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];

            // Получаем информацию о пользователях, за исключением создателя
            $users = $group->users->reject(function ($groupUser) use ($user) {
                return $groupUser->id === $user->id;
            })->map(function ($groupUser) {
                return [
                    'id' => $groupUser->id,
                    'name' => $groupUser->name,
                    'email' => $groupUser->email,
                ];
            })->toArray();

            // Добавляем информацию о пользователях в группу
            $groupArray['users'] = $users;

            return $groupArray;
        });

        $groupsUserInvited = $user->groups->reject(function ($group) use ($user) {
            return $group->owner_id === $user->id;
        })->map(function ($group) {
            $creator = User::find($group->owner_id);
            $groupArray = $group->toArray();
            $groupArray['creator'] = [
                'id' => $creator->id,
                'name' => $creator->name,
                'email' => $creator->email,
            ];

            // Получаем информацию о пользователях, за исключением создателя
            $users = $group->users->reject(function ($groupUser) use ($creator) {
                return $groupUser->id === $creator->id;
            })->map(function ($groupUser) {
                return [
                    'id' => $groupUser->id,
                    'name' => $groupUser->name,
                    'email' => $groupUser->email,
                ];
            })->values()->toArray();

            // Добавляем информацию о пользователях в группу
            $groupArray['users'] = $users;

            return $groupArray;
        })->values();

        return response()->json([
            'groupsCreatedByUser' => $groupsCreatedByUser,
            'groupsUserInvited' => $groupsUserInvited,
        ], 200);
    }

    public function store(Request $request)
    {
        $code = Str::random(10);
        switch ($request->type_id) {
            case Subscriptions::FREE->value:
                Group::create([
                    'owner_id' => $request->user()->id,
                    'name' => $request->groupName,
                    'invitation_code' => $code,
                    'type_id' => $request->type_id,
                    'max_people' => 5
                ]);
                break;
            case Subscriptions::EXTENDED->value:
                Group::create([
                    'owner_id' => $request->user()->id,
                    'name' => $request->groupName,
                    'invitation_code' => $code,
                    'type_id' => $request->type_id,
                    'max_people' => $request->people_amount
                ]);
        }

        return response('created', 201);
    }



    public function invite(Request $request)
    {
        $group = Group::find($request->group_id);
        if ($group->exists()) {
            Notification::route('mail', $request->email)->notify(new InvitationMail($group->invitation_code,
                $request->user(), $group));
            return response('user invited', 200);
        }
        return response('group not found', 404);
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
