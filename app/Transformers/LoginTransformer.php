<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class LoginTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
    ];

    public function transform($data)
    {
        return [
            'access_token' => $data->access_token,
            'token_type'   => $data->token_type,
            'expires_in'   => $data->expires_in,
            'user'         => $data->user,
        ];
    }

    public function includeUser($data)
    {
        if (empty($data)){
            return null;
        }
        $user = $data->user;

        if (empty($user)){
            return null;
        }

        return $this->item($user, new UserTransformer());

    }
}
