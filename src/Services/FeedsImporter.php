<?php

namespace App\Services;

use App\Entity\Feed;
use App\Entity\FeedCategory;
use App\Repository\FeedCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class FeedsImporter
{
    /**
     * @var FeedCategoryRepository
     */
    private $feedCategoryRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        FeedCategoryRepository $feedCategoryRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->feedCategoryRepository = $feedCategoryRepository;
        $this->entityManager          = $entityManager;
    }

    /**
     * @param string $feedCategoryName
     * @param array  $feedsData
     */
    public function import(string $feedCategoryName, array $feedsData)
    {
        if (empty($feedsData)) {
            return;
        }

        $feedCategory = $this->feedCategoryRepository->findOneByName($feedCategoryName);

        if (!$feedCategory) {
            $feedCategory = new FeedCategory();
            $feedCategory->setName($feedCategoryName);
            $this->entityManager->persist($feedCategory);
        }

        foreach ($feedsData as $feedData) {
            $feed = new Feed();
            $feed->setData($feedData);
            $feed->setCategory($feedCategory);

            $this->entityManager->persist($feed);
        }

        $this->entityManager->flush();
    }
}
