<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Transformers\LoginTransformer;

use Dingo\Api\Routing\Helpers;

use Illuminate\Http\Request;


class AuthController extends Controller {
    use Helpers;

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|min:10|max:80',
                'password' => 'required|string|min:6|max:30',
            ]
        );

        $data = $this->authService->login($request->only(['email', 'password']));

        return $this->response->item((object) $data, new LoginTransformer);
    }

    public function refreshToken(Request $request, $user_id) {
        $this->authService->authenticateUser($user_id);

        $this->validate(
            $request,
            [
                'token' => 'required|min:20',
            ]
        );
        $old_token = $request['token'];
        $data = $this->authService->refreshToken($old_token);

        return $this->response->item((object) $data, new LoginTransformer);
    }
}
