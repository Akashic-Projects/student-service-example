<?php

namespace App\Helpers;

use Dingo\Api\Routing\Helpers;


class PaginationData
{
    use Helpers;

    public $content;

    public $pageIndex; // Current page's index
    public $pageCount; // Total number of pages

    public $pageRowCount; // Total number of rows in one page
    public $totalRowCount; // Total number of rows on all of pages

    public function __construct() {
    }

    public function constructFromData($content, $pageIndex, $pageRowCount, $totalRowCount) {
        $this->content = $content;
        $this->pageIndex = $pageIndex;

        if ($pageRowCount != 0 && $totalRowCount != 0) {
            $this->pageCount = ceil($totalRowCount/$pageRowCount);
        } else {
            $this->pageCount = 0;
        }

        $this->pageRowCount = $pageRowCount;
        $this->totalRowCount = $totalRowCount;

        return $this;
    }

    public function createResponse($transformer) {
        $meta = [
            'pageIndex' => $this->pageIndex,
            'pageCount' => $this->pageCount,
            'pageRowCount' => $this->pageRowCount,
            'totalRowCount' => $this->totalRowCount,
        ];

        return $this->response->collection($this->content, $transformer)
            ->setMeta($meta);
    }
}
