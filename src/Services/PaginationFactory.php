<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaginationFactory
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param int          $maxPerPage
     * @param int          $currentPage
     *
     * @return Pagerfanta
     */
    public function create(QueryBuilder $queryBuilder, int $maxPerPage, int $currentPage)
    {
        $adapter    = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }
}
