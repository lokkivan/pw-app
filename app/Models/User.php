<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
        HasFactory;

    /**
     * @var string
     */
    const ROLE_GUEST = 'guest';

    /**
     * @var string
     */
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string
     */
    const EMAIL_VALIDATION_RULE = 'sometimes|required|email|unique:users';

    /**
     * @var string
     */
    const NAME_VALIDATION_RULE = 'required|string|max:255';

    /**
     * @var string
     */
    const PASSWORD_VALIDATION_RULE = 'required|string|min:4';

    /**
     * @return array
     */
    public function validationRules(): array
    {
        return [
            'email' => self::EMAIL_VALIDATION_RULE,
            'name' => self::NAME_VALIDATION_RULE,
            'password' => self::PASSWORD_VALIDATION_RULE,
        ];
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->role === self::ROLE_ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        if ($this->role === self::ROLE_GUEST) {
            return true;
        }
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }
}
