<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'email_verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'role_id',
        'email_verified_at',
        'created_at',
        'updated_at',
        'password',
        'remember_token',
        'id',
        'email_verification_token',
    ];

    protected $with = ['role','subscription'];

    public function subscription()
    {
        return $this->hasOne(UserSubscription::class);
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function commentaries(): HasMany
    {
        return $this->hasMany(Commentary::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class);
    }

//    protected function RoleId(): Attribute
//    {
//        return Attribute::make(
//            get: fn(string $value) => Role::find($value)->name,
//        );
//    }


}
