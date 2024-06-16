<?php

namespace App\Http\Controllers;

use App\Enums\Subscriptions;
use App\Http\Requests\GroupFromRequest;
use App\Http\Requests\GroupInviteFormRequest;
use App\Models\Group;
use App\Models\User;
use App\Notifications\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $groupsCreatedByUser = $user->groupsCreated->makeHidden('users')->map(function ($group) use ($user) {
            $groupArray = $group->toArray();
            $groupArray['creator'] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];

//            // Получаем информацию о пользователях, за исключением создателя
//            $users = $group->users->reject(function ($groupUser) use ($user) {
//                return $groupUser->id === $user->id;
//            })->map(function ($groupUser) {
//                return [
//                    'id' => $groupUser->id,
//                    'name' => $groupUser->name,
//                    'email' => $groupUser->email,
//                ];
//            })->values()->toArray();
//
//            // Добавляем информацию о пользователях в группу
//            $groupArray['users'] = $users;

            return $groupArray;
        })->values();

        $groupsUserInvited = $user->groups->makeHidden('users')->reject(function ($group) use ($user) {
            return $group->owner_id === $user->id;
        })->map(function ($group) {
            $creator = User::find($group->owner_id);
            $groupArray = $group->toArray();
            $groupArray['creator'] = [
                'id' => $creator->id,
                'name' => $creator->name,
                'email' => $creator->email,
            ];

//            // Получаем информацию о пользователях, за исключением создателя
//            $users = $group->users->reject(function ($groupUser) use ($creator) {
//                return $groupUser->id === $creator->id;
//            })->map(function ($groupUser) {
//                return [
//                    'id' => $groupUser->id,
//                    'name' => $groupUser->name,
//                    'email' => $groupUser->email,
//                ];
//            })->values()->toArray();
//
//            // Добавляем информацию о пользователях в группу
//            $groupArray['users'] = $users;

            return $groupArray;
        })->values();

        return response()->json([
            'groupsCreatedByUser' => $groupsCreatedByUser,
            'groupsUserInvited' => $groupsUserInvited,
        ], 200);
    }

    public function show(Request $request, Group $group)
    {
        $creator = User::find($group->owner_id);
        return response()->json([
            'id' => $group->id,
            'name' => $group->name,
            'max_people' => $group->max_people,
            'type_id' => $group->type_id,
            'creator' => $creator->makeHidden(['subscription']),
            'users' => $group->users->except($creator->id)->makeHidden(['subscription'])
        ]);
    }

    public function store(GroupFromRequest $request)
    {
        $code = Str::random(10);
        switch ($request->type_id) {
            case Subscriptions::FREE->value:
                $group = Group::create([
                    'owner_id' => $request->user()->id,
                    'project_id' => $request->project_id,
                    'name' => $request->groupName,
                    'invitation_code' => $code,
                    'type_id' => $request->type_id,
                    'max_people' => 5
                ]);
                $request->user()->groups()->attach($group);
                break;
            case Subscriptions::EXTENDED->value:
                $group = Group::create([
                    'owner_id' => $request->user()->id,
                    'project_id' => $request->project_id,
                    'name' => $request->groupName,
                    'invitation_code' => $code,
                    'type_id' => $request->type_id,
                    'max_people' => $request->people_amount
                ]);
                $request->user()->groups()->attach($group);
        }

        return response('created', 201);
    }


    public function invite(GroupInviteFormRequest $request)
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

    public function getCreateGroupInfo(Request $request)
    {
        $linkedProjects = $request->user()->groupsCreated->pluck('project_id');

        return response()->json([
            'price' => 100,
            'projects' => $request->user()->projects->whereNotInStrict('id', $linkedProjects)->values()
                ->makeHidden(['status_id', 'description', 'documentation']),
        ]);
    }

    public function destroy(Request $request, Group $group)
    {
        Gate::authorize('delete', $group);
        $group->delete();
        return response('deleted', 204);
    }

    public function deleteUserFromGroup(Group $group, User $user)
    {
        Gate::authorize('deleteUserFromGroup', $group);
        DB::table('user_group')
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->delete();
        return response('deleted', 204);
    }
}
