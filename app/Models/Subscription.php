<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name', 'description', 'price',
    ];

    public function user()
    {
        return $this->hasMany(UserSubscription::class);
    }

}
