<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\Akashic\CourseRecommendationService;
use App\Services\AuthService;
use App\Services\CourseRecomService;
use App\Services\UserNotifService;
use App\Transformers\CourseRecomTransformer;
use App\Transformers\UserNotifTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;


class UserNotifController extends Controller
{
    use Helpers;

    private $authService;
    private $userNotifService;
    private $crs;

    public function __construct(AuthService $authService,
                                UserNotifService $userNotifService,
                                CourseRecommendationService $crs) {
        $this->authService = $authService;
        $this->userNotifService = $userNotifService;
        $this->crs = $crs;
    }

    public function create(Request $request, int $user_id) {

        $this->authService->authorizeUser(["akashic"]);

        $this->validate(
            $request,
            [
                'course_id'   => 'required|integer',
                'ignored'     => 'required|boolean',
            ]
        );

        $un = $this->userNotifService->create($request, $user_id);

        return $this->response->item($un, new UserNotifTransformer());
    }

    public function findById(Request $request, $user_id, $un_id)
    {
        $this->authService->authenticateUser($user_id);

        $un = $this->userNotifService->findById($un_id);
        return $this->response->item($un, new UserNotifTransformer());
    }

    public function findAll(Request $request, $user_id)
    {
        $this->authService->authenticateUser($user_id);

        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->userNotifService->findAll($paginationQuery, $user_id);

        return $paginationData->createResponse(new UserNotifTransformer());
    }

    public function update(Request $request, $user_id, $un_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic", "student"]);

        $this->validate(
            $request,
            [
                'ignored'     => 'required|boolean',
            ]
        );

        //$ur = $this->courseRecomService->findById($ur_id);
        //$this->crs->remove_one_course_recom($ur);

        $un = $this->userNotifService->update($request, $un_id);

        //$this->crs->add_one_course_recom($ur);

        return $this->response->item($un, new UserNotifTransformer());
    }

    public function delete($user_id, $un_id) {

        $this->authService->authenticateUser($user_id);
        $this->authService->authorizeUser(["akashic"]);

        $un = $this->userNotifService->findById($un_id);
        $this->crs->remove_one_user_notif($un);

        $this->userNotifService->delete($un_id);
    }
}
