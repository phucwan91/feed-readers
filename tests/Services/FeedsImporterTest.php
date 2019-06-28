<?php

namespace App\Tests\Services;

use App\Entity\Feed;
use App\Entity\FeedCategory;
use App\Repository\FeedCategoryRepository;
use App\Repository\FeedRepository;
use App\Services\FeedsImporter;
use App\Tests\DatabaseTestCase;
use Doctrine\ORM\NonUniqueResultException;

class FeedsImporterTest extends DatabaseTestCase
{
    /** @var FeedCategoryRepository */
    private $feedCategoryRepository;

    /** @var FeedRepository */
    private $feedRepository;

    public function testImportEmptyData()
    {
        $feedCategoryRepository = $this->getFeedCategoryRepository();
        $feedRepository = $this->getFeedRepository();

        $importer = new FeedsImporter($feedCategoryRepository, $this->entityManager);

        $importer->import('test-category', []);

        $this->assertEquals(0, count($feedRepository->findAll()));
    }

    /**
     * @param string $categoryName
     * @param array|string[] $feedsData
     * @throws NonUniqueResultException
     *
     * @dataProvider feedDataProvider
     */
    public function testImport(string $categoryName, array $feedsData)
    {
        $feedCategoryRepository = $this->getFeedCategoryRepository();
        $feedRepository = $this->getFeedRepository();

        $importer = new FeedsImporter($feedCategoryRepository, $this->entityManager);

        $importer->import($categoryName, $feedsData);

        $this->assertEquals(empty($feedsData) ? 0 : 1, count($feedCategoryRepository->findAll()));
        $this->assertEquals(count($feedsData), count($feedRepository->findAll()));
    }

    public function feedDataProvider()
    {
        return [
            'empty data' => [
                'category name' => 'testCategory',
                'feedsData'     => [],
            ],
            'one feed' => [
                'category name' => 'testCategory',
                'feedsData'     => ['feed1'],
            ],
            'multiple feeds' => [
                'category name' => 'testCategory',
                'feedsData'     => ['feed1', 'feed2', 'feed3'],
            ],
        ];
    }

    public function testImportFeedsWithExistingCategory()
    {
        $feedCategoryRepository = $this->getFeedCategoryRepository();
        $feedRepository = $this->getFeedRepository();

        $importer = new FeedsImporter($feedCategoryRepository, $this->entityManager);

        $this->assertEquals(0, count($feedCategoryRepository->findAll()));

        $importer->import('categoryName', ['feed1']);
        $this->assertEquals(1, count($feedCategoryRepository->findAll()));
        $this->assertEquals(1, count($feedRepository->findAll()));

        $importer->import('categoryName', ['feed2', 'feed3']);
        $this->assertEquals(1, count($feedCategoryRepository->findAll()));
        $this->assertEquals(3, count($feedRepository->findAll()));
    }

    /**
     * @return FeedCategoryRepository
     */
    public function getFeedCategoryRepository(): FeedCategoryRepository
    {
        if (!$this->feedCategoryRepository) {
            $this->feedCategoryRepository = $this->entityManager->getRepository(FeedCategory::class);
        }

        return $this->feedCategoryRepository;
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
