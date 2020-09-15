<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotif extends Model {
    use SoftDeletes;

    protected $table = 'user_notifs';

    protected $fillable = [
        'user_id',
        'course_id',
        'ignored',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'course_id'    => 'integer',
        'ignored'      => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withTrashed();
    }

    public function course()
    {
        return $this->hasOne('App\Models\Course','id', 'course_id')->withTrashed();
    }
}
