<?php

namespace App\Http\Controllers;

use App\Enums\Subscriptions;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function checkSubscription(Request $request)
    {

    }

    public function orderSubscription(Request $request)
    {
        $user = $request->user();
        $months = $request->months;
        $sub = Subscription::find($request->id);

        if (!isset($request->id)) return response()->json(['error' => 'no subscriptions id data'], 409);


        if (DB::table('user_subscriptions')->find($user->id, 'user_id'))
            return response()->json(['error' => 'Subscription already bought'], 409);


        if ($sub->exists()) {
            switch ($sub->id) {
                case Subscriptions::FREE->value:
                    DB::table('user_subscriptions')->insert(
                        [
                            'user_id' => $user->id,
                            'subscription_id' => $request->id,
                            'active' => true,
                            'end_date' => Carbon::now()->addMonths(1)->toDateTimeString(),
                            'created_at' => Carbon::now()->toDateTimeString(),
                        ]
                    );
                    return response()->json([], 201);
                case Subscriptions::EXTENDED->value:
                    if (!isset($months)) return response()->json(['error' => 'no month data'], 409);
                    DB::table('user_subscriptions')->insert(
                        [
                            'user_id' => $user->id,
                            'subscription_id' => $request->id,
                            'active' => true,
                            'end_date' => Carbon::now()->addMonths((int)$months)->toDateTimeString(),
                            'created_at' => Carbon::now()->toDateTimeString(),
                        ]
                    );
                    return response()->json([], 201);
            }
        }
    }

    public function index(Request $request)
    {
        return response()->json(Subscription::all());
    }
}
