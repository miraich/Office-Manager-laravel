<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commentary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }
}
