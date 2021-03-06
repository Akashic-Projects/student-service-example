<?php

namespace App\Transformers;

use App\Models\UserCourse;
use League\Fractal\TransformerAbstract;


class UserCourseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'course',
    ];
    public function transform(UserCourse $uc)
    {

        if (empty($uc)){
            return null;
        }
        return [
            'id'            => $uc->id,
            'user_id'       => $uc->user_id,
            'course_id'     => $uc->course_id,
            'grade'         => $uc->grade,
            'rating'        => $uc->rating,
            'enrolled'      => $uc->enrolled,
        ];
    }

    public function includeCourse($data)
    {
        if (empty($data)){
            return null;
        }
        $course = $data->course;

        if (empty($course)){
            return null;
        }

        return $this->item($course, new CourseTransformer());

    }
}
