<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\AuthService;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;


class UserController extends Controller
{
    use Helpers;

    private $authService;
    private $userService;

    public function __construct(AuthService $authService,
                                UserService $userService) {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function test() {
        return "OK!";
    }

    public function create(Request $request) {

        $this->validate(
            $request,
            [
                'email'    => 'required|email||min:4|max:80|unique:users,email,NULL,id,deleted_at,NULL',
                'password' => 'required|string|min:3|max:30',
                'name'     => 'required|string|min:2|max:60',
                'age'     => 'required|integer',
            ]
        );

        $user = $this->userService->create($request);
        return $this->response->item($user, new UserTransformer());
    }

    public function findById(Request $request, $user_id)
    {
        $user = $this->authService->authenticateUser($user_id);

        return $this->response->item($user, new UserTransformer());
    }

    public function findAll(Request $request)
    {
        $this->authService->authorizeUser(["admin", "akashic"]);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->userService->findAll($paginationQuery);

        return $paginationData->createResponse(new UserTransformer());
    }

    public function delete($user_id) {
        $this->authService->authenticateUser($user_id);

        $this->userService->delete($user_id);
    }
}
