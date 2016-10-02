<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @mixin \Eloquent
 * @property integer $id
 * @property string $facebook_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @property string $facebook_token
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFacebookToken($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FitnessActivity[] $fitnessActivities
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
    protected $fillable = [
        'name', 'email', 'facebook_id', 'facebook_token', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'facebook_token', 'password', 'remember_token',
    ];

    /**
     * Check if facebook account is connected to user.
     *
     * @return bool
     */
    public function isFacebookConnected()
    {
        return $this->facebook_id === null ? false : true;
    }

    /**
     * User has many fitness activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fitnessActivities()
    {
        return $this->hasMany(FitnessActivity::class);
    }
}
