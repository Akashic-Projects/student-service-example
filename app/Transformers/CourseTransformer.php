<?php

namespace App\Transformers;

use App\Models\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    public function transform(Course $course)
    {
        if (empty($course)){
            return null;
        }
        return [
            'id'            => $course->id,
            'name'          => $course->name,
            'start_date'    => $course->start_date->format('d.m.Y.'),
            'end_date'      => $course->end_date->format('d.m.Y.'),
        ];
    }
}
