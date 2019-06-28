<?php

namespace App\Repository;

use App\Entity\Feed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Feed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feed[]    findAll()
 * @method Feed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Feed::class);
    }

    public function createFindAllQueryBuilder()
    {
        return $this->createQueryBuilder('feed')
            ->addSelect('feedCategory')
            ->leftJoin('feed.category', 'feedCategory');
    }

    public function createFindByCategoryIdsQueryBuilder(array $categoryIds)
    {
        $qb = $this->createFindAllQueryBuilder();

        return $qb->andWhere($qb->expr()->in('feedCategory.id', $categoryIds));
    }
}
