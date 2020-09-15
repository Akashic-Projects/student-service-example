<?php

namespace App\Transformers;

use App\Models\UserSubject;
use League\Fractal\TransformerAbstract;


class UserSubjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'subject',
    ];
    public function transform(UserSubject $us)
    {
        if (empty($us)){
            return null;
        }
        return [
            'id'            => $us->id,
            'user_id'       => $us->user_id,
            'subject_id'    => $us->subject_id,
            'rating'        => $us->rating,
        ];
    }

    public function includeSubject($data)
    {
        if (empty($data)){
            return null;
        }
        $subject = $data->subject;

        if (empty($subject)){
            return null;
        }

        return $this->item($subject, new SubjectTransformer());

    }
}
