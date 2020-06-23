<?php

namespace App\Http\Controllers;

use App\Services\Akashic\BruteForceDetectionService;
use App\Services\AuthService;
use App\Transformers\LoginTransformer;

use Dingo\Api\Routing\Helpers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class AuthController extends Controller {
    use Helpers;

    private $authService;
    private $bruteForceDetectionService;

    public function __construct(AuthService $authService,
                                BruteForceDetectionService $bruteForceDetectionService)
    {
        $this->authService = $authService;
        $this->bruteForceDetectionService = $bruteForceDetectionService;
    }

    public function login(Request $request)
    {
        $c1 = $this->bruteForceDetectionService->check_brute_force($request);
        if (!$c1) {
            throw new AccessDeniedHttpException('Brute force detected. Back off!');
        }

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
