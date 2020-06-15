<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\AuthService;
use App\Services\CourseService;
use App\Transformers\CourseTransformer;
use App\Transformers\UserCourseTransformer;
use Carbon\Carbon;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CourseController extends Controller
{
    use Helpers;

    private $authService;
    private $courseService;

    public function __construct(AuthService $authService,
                                CourseService $courseService) {
        $this->authService = $authService;
        $this->courseService = $courseService;
    }

    public function create(Request $request) {
        $this->authService->authorizeUser(["admin", "akashic", "sservice"]);

        $this->validate(
            $request,
            [
                'name'         => 'required|string|min:2|max:200',
                'start_date'   => 'required|string',
                'end_date'     => 'required|string',
            ]
        );
        $start_date = Carbon::createFromFormat('d.m.Y.', $request->start_date);
        $end_date = Carbon::createFromFormat('d.m.Y.', $request->end_date);

        $user = $this->courseService->create($request, $start_date, $end_date);
        return $this->response->item($user, new CourseTransformer());
    }

    public function findById(Request $request, $course_id)
    {
        $course = $this->courseService->findById($course_id);

        if ($course == null) {
            throw new BadRequestHttpException("Course with given id does not exist.");
        }

        return $this->response->item($course, new CourseTransformer());
    }

    public function findAll(Request $request)
    {
        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->courseService->findAll($paginationQuery);

        return $paginationData->createResponse(new CourseTransformer());
    }

    public function update(Request $request, $course_id) {
        $this->authService->authorizeUser(["akashic", "admin"]);

        $this->validate(
            $request,
            [
                'name'         => 'required|string|min:2|max:200',
                'start_date'   => 'required|string',
                'end_date'     => 'required|string',
            ]
        );

        $uc = $this->courseService->update($request, $course_id);
        return $this->response->item($uc, new CourseTransformer());
    }

    public function delete($course_id) {
        $this->authService->authorizeUser(["admin", "akashic", "sservice"]);

        $this->courseService->delete($course_id);
    }
}
