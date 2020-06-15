<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\CourseRecom;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserCourseService
{
    private $userService;
    private $courseService;

    public function __construct(UserService $userService,
                                CourseService $courseService)
    {
        $this->userService = $userService;
        $this->courseService = $courseService;
    }

    public function create(Request $request, $user_id) {
        $user = $this->userService->findById($user_id);
        if ($user == null) {
            throw new BadRequestHttpException("User with given id does not exist.");
        }

        $attributes = $request->all();

        $course = $this->courseService->findById($attributes['course_id']);
        if ($course == null) {
            throw new BadRequestHttpException("Course with given id does not exist.");
        }
        $found = UserCourse::where("user_id", "=", $user_id)
            ->where("course_id", "=", $attributes['course_id'])->first();
        if ($found != null) {
            throw new BadRequestHttpException("User-course info for given user already exists.");
        }

        $attributes['user_id'] = $user_id;

        return UserCourse::create($attributes);
    }

    public function findById($uc_id)
    {
        return UserCourse::find($uc_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'           => 'user_courses.id',
        'grade'        => 'user_courses.grade',
        'rating'       => 'user_courses.rating',
        'enrolled'     => 'user_courses.enrolled',
        'finished'     => 'user_courses.finished',
        'name'         => 'LOWER(courses.name)',
        'startDate'    => 'courses.start_date',
        'endDate'      => 'courses.end_date',
    ];
    public $searchAvailableFields = [
        'courseName'   => 'LOWER(courses.name)',
    ];

    public function findAll(PaginationQuery $paginationQuery, $user_id)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = UserCourse::select('user_courses.*')
            ->join('users', 'users.id', '=', 'user_courses.user_id')
            ->join('courses', 'courses.id', '=', 'user_courses.course_id')
            ->where('users.id', '=', $user_id);

        if ($paginationQuery->searchByField != null && $paginationQuery->searchString != null) {
            $fieldCount = count($paginationQuery->searchByField);
            for ($i = 0; $i < $fieldCount; $i++) {
                if (!array_key_exists($paginationQuery->searchByField[$i], $this->searchAvailableFields)) {
                    throw new BadRequestHttpException("Bad search field.");
                }
                $query->where(DB::raw($this->searchAvailableFields[$paginationQuery->searchByField[$i]]), 'LIKE', '%'.$paginationQuery->searchString[$i].'%');
            }
        }

        if ($paginationQuery->sortByField != null) {
            if (!array_key_exists($paginationQuery->sortByField, $this->sortAvailableFields)) {
                throw new BadRequestHttpException("Bad sort field.");
            }
            $query->orderBy(DB::raw($this->sortAvailableFields[$paginationQuery->sortByField]), $paginationQuery->sortOrder);
        }

        $count = $query->count();

        if ($paginationQuery->pageIndex != null && $paginationQuery->pageRowCount != null) {
            if ($paginationQuery->pageIndex < 1 || $paginationQuery->pageRowCount < 1) {
                throw new BadRequestHttpException("Bad page indexes and row counts.");
            }
            $query->skip(($paginationQuery->pageIndex-1)*$paginationQuery->pageRowCount)
                ->limit($paginationQuery->pageRowCount);
        }

        $content = $query->get();

        return (new PaginationData())->constructFromData(
            $content,
            $this->getZeroIfNull($paginationQuery->pageIndex),
            $this->getZeroIfNull($paginationQuery->pageRowCount),
            $count
        );
    }

    public function update(Request $request, $uc_id) {

        $uc = $this->findById($uc_id);
        if ($uc == null) {
            throw new BadRequestHttpException("UserCourse with given id does not exist.");
        }

        $attributes = $request->all();

        $uc->grade = $attributes['grade'];
        $uc->rating = $attributes['rating'];
        $uc->enrolled = $attributes['enrolled'];
        $uc->finished  = $attributes['finished'];

        $uc->save();

        return $uc;

    }

    public function delete($uc_id) {
        $uc = $this->findById($uc_id);

        if ($uc == null) {
            throw new BadRequestHttpException("UserCourse with given id does not exist.");
        }

        $uc->delete();
    }
}
