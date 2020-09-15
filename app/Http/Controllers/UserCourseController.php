<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\Akashic\CourseRecommendationService;
use App\Services\AuthService;
use App\Services\UserCourseService;
use App\Transformers\UserCourseTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;


class UserCourseController extends Controller
{
    use Helpers;

    private $authService;
    private $userCourseService;
    private $crs;

    public function __construct(AuthService $authService,
                                UserCourseService $userCourseService,
                                CourseRecommendationService $crs) {
        $this->authService = $authService;
        $this->userCourseService = $userCourseService;
        $this->crs = $crs;
    }

    public function create(Request $request, int $user_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $this->validate(
            $request,
            [
                'course_id' => 'required|integer',
                'grade'     => 'required|integer',
                'rating'    => 'required|integer',
                'enrolled'  => 'required|boolean',
            ]
        );

        $uc = $this->userCourseService->create($request, $user_id);
        $this->crs->add_one_user_course($uc);

        return $this->response->item($uc, new UserCourseTransformer());
    }

    public function findById(Request $request, $user_id, $uc_id)
    {
        $this->authService->authenticateUser($user_id);

        $uc = $this->userCourseService->findById($uc_id);
        return $this->response->item($uc, new UserCourseTransformer());
    }

    public function findAll(Request $request, $user_id)
    {
        $this->authService->authenticateUser($user_id);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->userCourseService->findAll($paginationQuery, $user_id);

        return $paginationData->createResponse(new UserCourseTransformer());
    }

    public function update(Request $request, $user_id, $uc_id) {
        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $this->validate(
            $request,
            [
                'grade'     => 'required|integer',
                'rating'    => 'required|integer',
                'enrolled'  => 'required|boolean',
            ]
        );

        $us = $this->userCourseService->findById($uc_id);
        $this->crs->remove_one_user_course($us);

        $uc = $this->userCourseService->update($request, $uc_id);

        $this->crs->add_one_user_course($uc);

        return $this->response->item($uc, new UserCourseTransformer());
    }

    public function delete($user_id, $uc_id) {
        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["student"]);

        $us = $this->userCourseService->findById($uc_id);
        $this->crs->remove_one_user_course($us);

        $this->userCourseService->delete($uc_id);
    }
}
