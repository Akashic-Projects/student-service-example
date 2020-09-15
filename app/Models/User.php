<?php

namespace  App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'authority_id',

        'name',
        'age',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'authority_id'  => 'integer',

        'name'          => 'string',
        'age'           => 'integer',
        'email'         => 'string',
        'password'      => 'string',

    ];

    public function authority()
    {
        return $this->hasOne('App\Models\Authority', 'id', 'authority_id');
    }

    public function recoms()
    {
        return $this->hasMany('App\Models\CourseRecom', 'user_id');
    }

    public function courses()
    {
        return $this->hasMany('App\Models\UserCourse', 'user_id');
    }

    public function hasAuthority($authority_name)
    {
        return strcmp($this->authority->name, $authority_name) == 0;
    }

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

