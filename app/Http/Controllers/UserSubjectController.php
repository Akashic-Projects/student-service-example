<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\AuthService;
use App\Services\UserSubjectService;
use App\Transformers\UserSubjectTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;


class UserSubjectController extends Controller
{
    use Helpers;

    private $authService;
    private $userSubjectService;

    public function __construct(AuthService $authService,
                                UserSubjectService $userSubjectService) {
        $this->authService = $authService;
        $this->userSubjectService = $userSubjectService;
    }

    public function create(Request $request, int $user_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $this->validate(
            $request,
            [
                'subject_id' => 'required|integer',
                'rating'    => 'required|integer',
            ]
        );

        $us = $this->userSubjectService->create($request, $user_id);
        return $this->response->item($us, new UserSubjectTransformer());
    }

    public function findById(Request $request, $user_id, $us_id)
    {
        $this->authService->authenticateUser($user_id);

        $us = $this->userSubjectService->findById($us_id);
        return $this->response->item($us, new UserSubjectTransformer());
    }

    public function findAll(Request $request, $user_id)
    {
        $this->authService->authenticateUser($user_id);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->userSubjectService->findAll($paginationQuery, $user_id);

        return $paginationData->createResponse(new UserSubjectTransformer());
    }

    public function update(Request $request, $user_id, $us_id) {
        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $this->validate(
            $request,
            [
                'rating'    => 'required|integer',
            ]
        );

        $us = $this->userSubjectService->update($request, $us_id);
        return $this->response->item($us, new UserSubjectTransformer());
    }

    public function delete($user_id, $us_id) {
        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $this->userSubjectService->delete($us_id);
    }
}
