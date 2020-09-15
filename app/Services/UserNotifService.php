<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\UserNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserNotifService
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

        $found = UserNotif::where("user_id", "=", $user_id)
            ->where("course_id", "=", $attributes['course_id'])->first();
        if ($found != null) {
            throw new BadRequestHttpException("Notification for given course already exists.");
        }

        $attributes['user_id'] = $user_id;

        return UserNotif::create($attributes);
    }

    public function findById($un_id)
    {
        return UserNotif::find($un_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'             => 'user_notifs.id',
        'ignored'        => 'user_notifs.ignored',
    ];

    public function findAll(PaginationQuery $paginationQuery, $user_id)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = UserNotif::select('user_notifs.*')
            ->join('users', 'users.id', '=', 'user_notifs.user_id')
            ->join('courses', 'courses.id', '=', 'user_notifs.course_id')
            ->where('users.id', '=', $user_id)
            ->where('user_notifs.ignored', '=', 0);

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

    public function update(Request $request, $un_id) {

        $un = $this->findById($un_id);
        if ($un == null) {
            throw new BadRequestHttpException("UserNotif with given id does not exist.");
        }

        $attributes = $request->all();

        $un->ignored = $attributes['ignored'];

        $un->save();

        return $un;
    }

    public function delete($un_id) {
        $un = $this->findById($un_id);

        if ($un == null) {
            throw new BadRequestHttpException("UserNotif with given id does not exist.");
        }

        $un->delete();
    }
}
