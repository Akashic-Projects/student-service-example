<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends Model {
    use SoftDeletes;

    protected $table = 'user_courses';

    protected $fillable = [
        'user_id',
        'course_id',
        'grade',
        'rating',
        'enrolled',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'course_id'    => 'integer',
        'grade'        => 'integer',
        'rating'       => 'integer',
        'enrolled'     => 'boolean',
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
