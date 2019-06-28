<?php

namespace App\Tests\Services;

use App\Services\PaginationFactory;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;

class PaginationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new PaginationFactory();

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $pager = $factory->create($queryBuilder, 1, 1);

        $this->assertInstanceOf(Pagerfanta::class, $pager);
    }
}
