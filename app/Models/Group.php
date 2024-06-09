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
        'max_people'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
        'invitation_code',
        'owner_id'
    ];

    protected $with = ['users'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_group');
    }

}
