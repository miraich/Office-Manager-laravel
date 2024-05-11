<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status_id',
        'end_date'
    ];

    public function commentaries()
    {
        return $this->belongsToMany(Commentary::class,
            'commentary_task', 'task_id', 'commentary_id')->withTimestamps();
    }
}
