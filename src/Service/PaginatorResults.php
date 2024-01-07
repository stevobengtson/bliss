<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorResults
{
    public int $page = 1;
    public int $itemsPerPage = 25;
    public int $totalItems = 0;
    public int $pages = 0;
    /** @var Paginator<mixed> */
    public Paginator $members;

    /**
     * @param Paginator<mixed> $members
     */
    public function __construct(
        Paginator $members,
        int $page = 1,
        int $itemsPerPage = 25,
        int $totalItems = 0,
        int $pages = 0
    ) {
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalItems = $totalItems;
        $this->pages = $pages;
        $this->members = $members;
    }

    /**
     * @return array{
     *     page: int,
     *     itemsPerPage: int,
     *     totalItems: int,
     *     pages: int,
     *     members: Paginator<mixed>
     * }
     */
    public function __toArray(): array
    {
        return [
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'totalItems' => $this->totalItems,
            'pages' => $this->pages,
            'members' => $this->members,
        ];
    }
}
