<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $appends = [
        'date',
        'time'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id',
        'user_id',
        'chat_id',
        'text'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDateAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->toDateString();
    }


    public function getTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->toTimeString();
    }
}
