<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerificationMail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    ];

    protected $with = ['roles'];

    protected $appends = ['is_subscribed', 'subscription_end_date', 'subscription_type'];

    protected function subscriptionType(): Attribute
    {
        return new Attribute(
            get: fn() => UserSubscription::where('user_id', $this->id)->get()->first()->subscription_id,
        );
    }

    protected function subscriptionEndDate(): Attribute
    {
        return new Attribute(
            get: fn() => UserSubscription::where('user_id', $this->id)->get()->first()->end_date,
        );
    }

    protected function isSubscribed(): Attribute
    {
        return new Attribute(
            get: fn() => UserSubscription::where('user_id', $this->id)->get()->first()->active,
        );
    }

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

    public function roles(): HasOne
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
