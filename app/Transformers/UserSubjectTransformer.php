<?php

namespace App\Transformers;

use App\Models\UserSubject;
use League\Fractal\TransformerAbstract;


class UserSubjectTransformer extends TransformerAbstract
{
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
}
