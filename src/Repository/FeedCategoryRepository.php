<?php

namespace App\Repository;

use App\Entity\FeedCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedCategory[]    findAll()
 * @method FeedCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedCategory::class);
    }

    /**
     * @param string $name
     *
     * @return FeedCategory|null
     */
    public function findOneByName(string $name): ?FeedCategory
    {
        return $this->findOneBy([
            'name' => $name
        ]);
    }
}
