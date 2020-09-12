<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\UserSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserSubjectService
{
    private $userService;
    private $subjectService;

    public function __construct(UserService $userService,
                                SubjectService $subjectService)
    {
        $this->userService = $userService;
        $this->subjectService = $subjectService;
    }

    public function create(Request $request, $user_id) {
        $user = $this->userService->findById($user_id);
        if ($user == null) {
            throw new BadRequestHttpException("User with given id does not exist.");
        }

        $attributes = $request->all();
        $subject = $this->subjectService->findById($attributes['subject_id']);

        if ($subject == null) {
            throw new BadRequestHttpException("Subject with given id does not exist.");
        }
        $found = UserSubject::where("user_id", "=", $user_id)
            ->where("subject_id", "=", $attributes['subject_id'])->first();
        if ($found != null) {
            throw new BadRequestHttpException("User-subject info for given user already exists.");
        }

        $attributes['user_id'] = $user_id;
        return UserSubject::create($attributes);
    }

    public function findById($us_id)
    {
        return UserSubject::find($us_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'           => 'user_subjects.id',
        'rating'       => 'user_subjects.rating',
        'name'         => 'LOWER(subjects.name)',
    ];
    public $searchAvailableFields = [
        'subjectName'   => 'LOWER(subjects.name)',
    ];

    public function findAll(PaginationQuery $paginationQuery, $user_id)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = UserSubject::select('user_subjects.*')
            ->join('users', 'users.id', '=', 'user_subjects.user_id')
            ->join('subjects', 'subjects.id', '=', 'user_subjects.subject_id')
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

    public function update(Request $request, $us_id) {

        $us = $this->findById($us_id);
        if ($us == null) {
            throw new BadRequestHttpException("UserSubject with given id does not exist.");
        }

        $attributes = $request->all();

        $us->rating = $attributes['rating'];
        $us->save();

        return $us;

    }

    public function delete($us_id) {
        $us = $this->findById($us_id);

        if ($us == null) {
            throw new BadRequestHttpException("UserSubject with given id does not exist.");
        }

        $us->delete();
    }
}
