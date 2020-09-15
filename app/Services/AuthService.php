<?php

namespace App\Services;

use Tymon\JWTAuth\JWTAuth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Carbon\Carbon;

class AuthService
{

    private $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

    public function login($credentials)
    {
        $token = auth()->attempt($credentials, ['exp' => Carbon::now()->addDays(7)->timestamp]);

        if (!$token) {
            throw new AccessDeniedHttpException('Login credentials incorrect.');
        }

        $user = $this->getLoggedInUser();

        return array(
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => $user,
        );
    }

    public function refreshToken($token) {
        $token = $this->jwtAuth->refresh($token);

        if (!$token) {
            throw new AccessDeniedHttpException('Provided token is corrupted.');
        }

        $user = $this->getLoggedInUser();

        return array(
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => $user,
        );
    }

    public function logout()
    {
        auth()->logout();
    }

    // TODO: Refresh token function

    public function getLoggedInUser()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        return $user;
    }

    public function authenticateUser(int $claimed_user_id)
    {
        $user = $this->getLoggedInUser();

        if ($user == null) {
            throw new AccessDeniedHttpException("User not logged in.");
        }

        if ($user->hasAuthority("admin") ||
            $user->hasAuthority("akashic")) {
            return $user;
        }

        if ($user->id !== $claimed_user_id) {
            throw new AccessDeniedHttpException("Unauthorized action.");
        }

        return $user;
    }

    public function authorizeUser($authorities)
    {
        if ( $this->getLoggedInUser() == null) {
            throw new AccessDeniedHttpException("Unauthorized action. User not logged in.");
        }

        foreach ($authorities as $authority) {
            if ($this->getLoggedInUser()->hasAuthority($authority)) {
                return true;
            }
        }
        return abort(401, 'This action is unauthorized. User has no requred authority.');
    }

}
