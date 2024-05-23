<?php

namespace App\Models;

use App\Enums\Statuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'status_id',
        'name',
        'description',
        'budget',
        'end_date',
        'documentation'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'owner_id',
        'budget',
        'end_date',
        'tasks'
    ];

    protected $with = ['tasks'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function setCurrentStatus()
    {
        if ($this->tasks()->exists()) {

            $statuses = $this->tasks->pluck('status_id');

            if ($statuses->every(fn($status) => $status === Statuses::FINISHED->value)) {
                return Statuses::FINISHED->value;
            }

            if ($statuses->contains(Statuses::NOT_STARTED->value) && !$statuses->contains(Statuses::IN_PROGRESS->value) && !$statuses->contains(Statuses::FINISHED->value)) {
                return Statuses::NOT_STARTED->value;
            }

            if ($statuses->contains(Statuses::IN_PROGRESS->value) || $statuses->contains(Statuses::FINISHED->value)) {
                return Statuses::IN_PROGRESS->value;
            }
        }
        return Statuses::NOT_STARTED->value;
    }
}
