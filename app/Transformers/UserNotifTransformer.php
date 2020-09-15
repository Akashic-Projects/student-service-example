<?php

namespace App\Transformers;

use App\Models\UserNotif;
use League\Fractal\TransformerAbstract;


class UserNotifTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'course',
    ];
    public function transform(UserNotif $un)
    {

        if (empty($un)){
            return null;
        }
        return [
            'id'            => $un->id,
            'user_id'       => $un->user_id,
            'course_id'     => $un->course_id,
            'ignored'       => $un->ignored,
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
