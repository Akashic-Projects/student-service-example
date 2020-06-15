<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CourseService {

    public function create($request, $start_date, $end_date) {
        $user_attributes = $request->all();
        $user_attributes['start_date'] = $start_date;
        $user_attributes['end_date'] = $end_date;

        return Course::create($user_attributes);
    }

    public function findById($course_id)
    {
        return  Course::find($course_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'          => 'courses.id',
        'name'        => 'LOWER(courses.name)',
        'startDate'   => 'courses.start_date',
        'endDate'     => 'courses.end_date',
    ];
    public $searchAvailableFields = [
        'name'  => 'LOWER(courses.name)',
    ];

    public function findAll(PaginationQuery $paginationQuery)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = Course::select('courses.*');

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

    public function update(Request $request, $course_id) {

        $course = $this->findById($course_id);
        if ($course == null) {
            throw new BadRequestHttpException("Course with given id does not exist.");
        }

        $attributes = $request->all();

        $course->name = $attributes['name'];

        $start_date = Carbon::createFromFormat('d.m.Y.', $request->start_date);
        $end_date = Carbon::createFromFormat('d.m.Y.', $request->end_date);

        $course->start_date = $start_date;
        $course->end_date = $end_date;

        $course->save();

        return $course;

    }

    public function delete($course_id) {
        $course = $this->findById($course_id);

        if ($course == null) {
            throw new BadRequestHttpException("Course with given id does not exist.");
        }

        $course->delete();
    }
}
