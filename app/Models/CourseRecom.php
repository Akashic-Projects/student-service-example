<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRecom extends Model {
    use SoftDeletes;

    protected $table = 'courses_recom';

    protected $fillable = [
        'user_id',
        'course_id',
        'ignored',
        'accepted',
    ];

    protected $casts = [
        'user_id'     => 'integer',
        'course_id'   => 'integer',
        'ignored'     => 'boolean',
        'accepted'    => 'boolean',
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
