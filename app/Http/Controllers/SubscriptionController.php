<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function checkSubscription(Request $request)
    {

    }

    public function index(Request $request)
    {
        return response()->json(Subscription::all());
    }
}
