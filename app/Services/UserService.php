<?php

namespace App\Services;

use App\Helpers\PaginationData;
use App\Helpers\PaginationQuery;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService {

    public function create($request) {
        $user_attributes = $request->all();
        $user_attributes['password'] = Hash::make($user_attributes['password']);

        return User::create($user_attributes);
    }

    public function findById($user_id)
    {
        return  User::find($user_id);
    }

    public function getZeroIfNull($value) {
        if ($value == null) {
            return 0;
        }
        return $value;
    }
    public $sortAvailableFields = [
        'id'        => 'users.id',
        'name'      => 'LOWER(users.name)',
        'email'     => 'LOWER(users.email)',
        'createdAt' => 'users.created_at',
        'updatedAt' => 'users.updated_at',
    ];
    public $searchAvailableFields = [
        'email' => 'LOWER(users.email)',
        'name'  => 'LOWER(users.name)',
    ];

    public function findAll(PaginationQuery $paginationQuery)
    {
        if ($paginationQuery->sortOrder == null) {
            $paginationQuery->sortOrder = 'DESC';
        }
        if (!($paginationQuery->sortOrder == 'ASC' || $paginationQuery->sortOrder == 'DESC')) {
            throw new BadRequestHttpException("Bad sorting-order field.");
        }

        $query = User::select('users.*');

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

    public function delete($user_id) {
        $user = $this->findById($user_id);

        if ($user == null) {
            throw new BadRequestHttpException("User with given id does not exist.");
        }

        $user->delete();
    }
}
