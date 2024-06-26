<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'invitation_code',
        'type_id',
        'max_people',
        'project_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
        'invitation_code',
    ];

    protected $with = ['users'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_group');
    }

}
