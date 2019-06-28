<?php

namespace App\Tests\Repository;

use App\Entity\Feed;
use App\Entity\FeedCategory;
use App\Repository\FeedRepository;
use App\Tests\DatabaseTestCase;
use Doctrine\ORM\QueryBuilder;

class FeedRepositoryTest extends DatabaseTestCase
{
    /** @var FeedRepository */
    private $feedRepository;

    public function testCreateFindAllQueryBuilder()
    {
        $feedCategory = new FeedCategory();
        $feedCategory->setName('testCategory');

        $feed1 = new Feed();
        $feed1->setCategory($feedCategory);
        $feed1->setData('testFeedData1');

        $feed2 = new Feed();
        $feed2->setCategory($feedCategory);
        $feed2->setData('testFeedData2');

        $this->entityManager->persist($feedCategory);
        $this->entityManager->persist($feed1);
        $this->entityManager->persist($feed2);
        $this->entityManager->flush();

        $feedRepository = $this->getFeedRepository();

        $findAllQueryBuilder = $feedRepository->createFindAllQueryBuilder();

        $this->assertInstanceOf(QueryBuilder::class, $findAllQueryBuilder);

        $insertedFeeds = $findAllQueryBuilder->getQuery()->getResult();

        $this->assertEquals(2, count($insertedFeeds));
    }

    public function testCreateFindByCategoryIdsQueryBuilder()
    {
        $feedCategory1 = new FeedCategory();
        $feedCategory1->setName('testCategory1');

        $feedCategory2 = new FeedCategory();
        $feedCategory2->setName('testCategory2');

        $feed1 = new Feed();
        $feed1->setCategory($feedCategory1);
        $feed1->setData('testFeedData1');

        $feed2 = new Feed();
        $feed2->setCategory($feedCategory1);
        $feed2->setData('testFeedData2');

        $feed3 = new Feed();
        $feed3->setCategory($feedCategory2);
        $feed3->setData('testFeedData2');

        $this->entityManager->persist($feedCategory1);
        $this->entityManager->persist($feedCategory2);
        $this->entityManager->persist($feed1);
        $this->entityManager->persist($feed2);
        $this->entityManager->persist($feed3);
        $this->entityManager->flush();

        $feedRepository = $this->getFeedRepository();

        $findAllQueryBuilder = $feedRepository->createFindByCategoryIdsQueryBuilder([
            $feedCategory1->getId(),
            $feedCategory2->getId(),
        ]);
        $this->assertInstanceOf(QueryBuilder::class, $findAllQueryBuilder);

        $insertedFeeds = $findAllQueryBuilder->getQuery()->getResult();
        $this->assertEquals(3, count($insertedFeeds));

        $category1Feeds = $feedRepository
            ->createFindByCategoryIdsQueryBuilder([$feedCategory1->getId()])
            ->getQuery()
            ->getResult();
        $this->assertEquals(2, count($category1Feeds));

        $category2Feeds = $feedRepository
            ->createFindByCategoryIdsQueryBuilder([$feedCategory2->getId()])
            ->getQuery()
            ->getResult();
        $this->assertEquals(1, count($category2Feeds));
    }

    /**
     * @return FeedRepository
     */
    public function getFeedRepository(): FeedRepository
    {
        if (!$this->feedRepository) {
            $this->feedRepository = $this->entityManager->getRepository(Feed::class);
        }

        return $this->feedRepository;
    }

}