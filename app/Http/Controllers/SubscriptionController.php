<?php

namespace App\Http\Controllers;

use App\Enums\Subscriptions;
use App\Http\Requests\SubscriptionFormRequest;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function checkSubscription(Request $request)
    {

    }

    public function orderSubscription(SubscriptionFormRequest $request)
    {
        $user = $request->user();
        $months = $request->months;
        $sub = Subscription::find($request->id);

        if (DB::table('user_subscription')->find($user->id, 'user_id'))
            return response()->json(['error' => 'Subscription already bought'], 409);

        switch ($sub->id) {
            case Subscriptions::FREE->value:
                $user_sub = $user->subscription()->create([
                    'subscription_id' => $sub->id,
                    'active' => true,
                    'end_date' => Carbon::now()->addMonths(1)->toDateTime(),
                    'created_at' => Carbon::now()->toDateTimeString(),
                ]);
                return response()->json(['message' => 'Subscription successfully bought'], 201);
            case Subscriptions::EXTENDED->value:
                $user_sub = $user->subscription()->create([
                    'subscription_id' => $sub->id,
                    'active' => true,
                    'end_date' => Carbon::now()->addMonths((int)$months)->toDateTime(),
                    'created_at' => Carbon::now()->toDateTime(),
                ]);
                return response()->json(['message' => 'Subscription successfully bought'], 201);
        }
    }

    public function index(Request $request)
    {
        return response()->json(Subscription::all());
    }
}
