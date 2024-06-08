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
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
        'invitation_code'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_group');
    }

}
