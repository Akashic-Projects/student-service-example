<?php

namespace App\Transformers;

use App\Models\Subject;
use League\Fractal\TransformerAbstract;

class SubjectTransformer extends TransformerAbstract
{
    public function transform(Subject $subject)
    {
        if (empty($subject)){
            return null;
        }
        return [
            'id'            => $subject->id,
            'name'          => $subject->name,
        ];
    }
}
