<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'role_id', 'manager_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public const COL_ROLE_ID = 'role_id';
    public const COL_MANAGER_ID = 'manager_id';

    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
    /**
     * Set password attribute
     */
    public function setPasswordAttribute($value)
    {
        if ($value && is_string($value) && trim($value)) {
            $this->attributes['password'] = Hash::make(trim($value));
        }
    }
}
