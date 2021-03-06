<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\Akashic\CourseRecommendationService;
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
    private $crs;

    public function __construct(AuthService $authService,
                                CourseRecomService $courseRecomService,
                                CourseRecommendationService $crs) {
        $this->authService = $authService;
        $this->courseRecomService = $courseRecomService;
        $this->crs = $crs;
    }

    public function create(Request $request, int $user_id) {

        $this->authService->authorizeUser(["akashic"]);

        $this->validate(
            $request,
            [
                'course_id'   => 'required|integer',
                'ignored'     => 'required|boolean',
                'accepted'    => 'required|boolean',
                'priority'    => 'required|numeric'
            ]
        );

        $ur = $this->courseRecomService->create($request, $user_id);

        return $this->response->item($ur, new CourseRecomTransformer());
    }

    public function findById(Request $request, $user_id, $ur_id)
    {
        $this->authService->authenticateUser($user_id);

        $ur = $this->courseRecomService->findById($ur_id);
        return $this->response->item($ur, new CourseRecomTransformer());
    }

    public function findAll(Request $request, $user_id)
    {
        $this->authService->authenticateUser($user_id);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->courseRecomService->findAll($paginationQuery, $user_id);

        return $paginationData->createResponse(new CourseRecomTransformer());
    }

    public function update(Request $request, $user_id, $ur_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic", "student"]);

        $this->validate(
            $request,
            [
                'ignored'     => 'required|boolean',
                'accepted'    => 'required|boolean'
            ]
        );

        //$ur = $this->courseRecomService->findById($ur_id);
        //$this->crs->remove_one_course_recom($ur);

        $ur = $this->courseRecomService->update($request, $ur_id);

        //$this->crs->add_one_course_recom($ur);

        return $this->response->item($ur, new CourseRecomTransformer());
    }

    public function delete($user_id, $ur_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic"]);

        $ur = $this->courseRecomService->findById($ur_id);
        $this->crs->remove_one_user_course($ur);

        $this->courseRecomService->delete($ur_id);
    }
}
