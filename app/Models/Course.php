<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model {
    use SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'name'          => 'string',
        'start_date'    => 'date:d-m-Y',
        'end_date'      => 'date:d-m-Y',
    ];

    public function recoms()
    {
        return $this->hasMany('App\Models\CourseRecom', 'course_id');
    }

    public function userCourses()
    {
        return $this->hasMany('App\Models\UserCourse', 'course_id');
    }
}
