<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaginationQuery
{
    public $pageIndex; // Current page's index
    public $pageRowCount;  // Total number of rows in one page

    public $sortByField;
    public $sortOrder;

    public $searchByField;
    public $searchString;

    public function __construct()
    {
    }

    public function getValuesFromString($s) {
        $values = explode(',', $s);

        if ($values[0] == "") {
            return null;
        }

        return $values;
    }

    public function countElements($a) {
        if ($a == null) {
            return 0;
        }
        return count($a);
    }

    public function assemble(Request $request) {
        $this->pageIndex = $request->query('pageIndex');
        $this->pageRowCount = $request->query('pageRowCount');

        $this->sortByField = $request->query('sortField');
        $this->sortOrder = $request->query('sortOrder');

        $this->searchByField = $request->query('searchFields');
        $this->searchByField = $this->getValuesFromString($this->searchByField);

        $this->searchString  =  $request->query('searchStrings');
        $this->searchString = $this->getValuesFromString($this->searchString);

        if ($this->countElements($this->searchByField) != $this->countElements($this->searchString)) {
            throw new BadRequestHttpException("Search field number is not same as number of strings to be searched.");
        }

        return $this;
    }
}
