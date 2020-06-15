<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\AuthService;
use App\Services\CourseRecomService;
use App\Transformers\CourseRecomTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;


class CourseRecomController extends Controller
{
    use Helpers;

    private $authService;
    private $courseRecomService;

    public function __construct(AuthService $authService,
                                CourseRecomService $courseRecomService) {
        $this->authService = $authService;
        $this->courseRecomService = $courseRecomService;
    }

    public function create(Request $request, int $user_id) {

        $this->authService->authorizeUser(["akashic"]);

        $this->validate(
            $request,
            [
                'course_id'   => 'required|integer',
                'ignored'     => 'boolean',
                'accepted'    => 'boolean'
            ]
        );

        $uc = $this->courseRecomService->create($request, $user_id);
        return $this->response->item($uc, new CourseRecomTransformer());
    }

    public function findById(Request $request, $user_id, $uc_id)
    {
        $this->authService->authenticateUser($user_id);

        $uc = $this->courseRecomService->findById($uc_id);
        return $this->response->item($uc, new CourseRecomTransformer());
    }

    public function findAll(Request $request, $user_id)
    {
        $this->authService->authenticateUser($user_id);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->courseRecomService->findAll($paginationQuery, $user_id);

        return $paginationData->createResponse(new CourseRecomTransformer());
    }

    public function update(Request $request, $user_id, $uc_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic", "student"]);

        $this->validate(
            $request,
            [
                'ignored'     => 'required|boolean',
                'accepted'    => 'required|boolean'
            ]
        );

        $uc = $this->courseRecomService->update($request, $uc_id);
        return $this->response->item($uc, new CourseRecomTransformer());
    }

    public function delete($user_id, $uc_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic"]);

        $this->courseRecomService->delete($uc_id);
    }
}
