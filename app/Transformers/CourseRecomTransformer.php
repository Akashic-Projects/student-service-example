<?php

namespace App\Transformers;

use App\Models\CourseRecom;
use League\Fractal\TransformerAbstract;


class CourseRecomTransformer extends TransformerAbstract
{
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
            'accepted'      => $ur->accepted
        ];
    }
}
