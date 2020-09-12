<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class SubjectService {

    public function create($request) {
        $user_attributes = $request->all();

        return Subject::create($user_attributes);
    }

    public function findById($subject_id)
    {
        return  Subject::find($subject_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }

    public $sortAvailableFields = [
        'id'          => 'subjects.id',
        'name'        => 'LOWER(subjects.name)',
    ];
    public $searchAvailableFields = [
        'name'  => 'LOWER(subjects.name)',
    ];

    public function findAll(PaginationQuery $paginationQuery)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = Subject::select('subjects.*');

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

    public function update(Request $request, $subject_id) {

        $subject = $this->findById($subject_id);
        if ($subject == null) {
            throw new BadRequestHttpException("Subject with given id does not exist.");
        }

        $attributes = $request->all();
        $subject->name = $attributes['name'];

        $subject->save();
        return $subject;

    }

    public function delete($subject_id) {
        $subject = $this->findById($subject_id);

        if ($subject == null) {
            throw new BadRequestHttpException("Subject with given id does not exist.");
        }

        $subject->delete();
    }
}
