<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        if (empty($user)){
            return null;
        }
        return [
            'id'            => $user->id,
            'name'          => $user->name,
            'age'           => $user->age,
            'email'         => $user->email,
            'authority'     => $user->authority->name
        ];
    }

}
