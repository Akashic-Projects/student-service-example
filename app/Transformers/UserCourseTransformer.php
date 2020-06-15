<?php

namespace App\Transformers;

use App\Models\UserCourse;
use League\Fractal\TransformerAbstract;


class UserCourseTransformer extends TransformerAbstract
{
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
            'finished'      => $uc->finished,
        ];
    }
}
