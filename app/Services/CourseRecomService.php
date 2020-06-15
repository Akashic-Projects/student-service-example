<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\CourseRecom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CourseRecomService
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

        $found = CourseRecom::where("user_id", "=", $user_id)
            ->where("course_id", "=", $attributes['course_id'])->first();
        if ($found != null) {
            throw new BadRequestHttpException("Course recomendation for given user already exists.");
        }

        $attributes['user_id'] = $user_id;

        return CourseRecom::create($attributes);
    }

    public function findById($cr_id)
    {
        return CourseRecom::find($cr_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'             => 'courses_recom.id',
        'ignored'        => 'courses_recom.ignored',
        'accepted'       => 'courses_recom.accepted',
    ];

    public function findAll(PaginationQuery $paginationQuery, $user_id)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = CourseRecom::select('courses_recom.*')
            ->join('users', 'users.id', '=', 'courses_recom.user_id')
            ->join('courses', 'courses.id', '=', 'courses_recom.course_id')
            ->where('users.id', '=', $user_id);

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

    public function update(Request $request, $cr_id) {

        $cr = $this->findById($cr_id);
        if ($cr == null) {
            throw new BadRequestHttpException("CourseRecomendation with given id does not exist.");
        }

        $attributes = $request->all();

        $cr->ignored = $attributes['ignored'];
        $cr->accepted = $attributes['accepted'];

        $cr->save();

        return $cr;
    }

    public function delete($cr_id) {
        $cr = $this->findById($cr_id);

        if ($cr == null) {
            throw new BadRequestHttpException("CourseRecomendation with given id does not exist.");
        }

        $cr->delete();
    }
}
