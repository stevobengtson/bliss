<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginatorTrait
{
    public function getPagedResults(QueryBuilder $queryBuilder, int $page, int $itemsPerPage): PaginatorResults
    {
        $paginator = new Paginator($queryBuilder);

        $totalItems = count($paginator);
        $pageCount = intval(ceil($totalItems / $itemsPerPage));

        $paginator
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);

        return new PaginatorResults($paginator, $page, $itemsPerPage, $totalItems, $pageCount);
    }
}
