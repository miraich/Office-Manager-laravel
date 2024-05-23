<?php

namespace App\Models;

use App\Enums\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status_id',
        'end_date'
    ];

    protected $hidden = ['project_id','created_at','updated_at'];


    public function commentaries() //хз пока
    {
        return $this->belongsToMany(Commentary::class,
            'commentary_task', 'task_id', 'commentary_id')->withTimestamps();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(Statuses::class);
    }
}
