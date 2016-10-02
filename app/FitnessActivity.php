<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FitnessActivity
 *
 * @property integer $id
 * @property string $facebook_id
 * @property string $type
 * @property float $distance
 * @property integer $calories
 * @property float $duration
 * @property \Carbon\Carbon $start_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereDistance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereCalories($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereStartTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $user_id
 * @method static \Illuminate\Database\Query\Builder|\App\FitnessActivity whereUserId($value)
 */
class FitnessActivity extends Model
{
    protected $table = 'fitness_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_id', 'type', 'distance', 'calories', 'duration', 'start_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'distance' => 'float',
        'calories' => 'integer',
        'duration' => 'float'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_time'
    ];

    /**
     * Fitness activity is assigned to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
