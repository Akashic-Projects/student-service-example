<?php

namespace App\Transformers;

use App\Models\CourseRecom;
use League\Fractal\TransformerAbstract;


class CourseRecomTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'course',
    ];
    public function transform(CourseRecom $ur)
    {
        if (empty($ur)){
            return null;
        }
        return [
            'id'            => $ur->id,
            'user_id'       => $ur->user_id,
            'course_id'     => $ur->course_id,
            'ignored'       => $ur->ignored,
            'accepted'      => $ur->accepted,
            'priority'      => $ur->priority + 0.000001,
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
