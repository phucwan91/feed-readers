<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use App\Repository\FeedCategoryRepository;
use App\Repository\FeedRepository;
use App\Services\PaginationFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FeedController extends AbstractController
{
    public function listAction(
        Request $request,
        FeedRepository $feedRepository,
        FeedCategoryRepository $categoryRepository,
        PaginationFactory $paginationFactory
    ) {
        $itemsPerPage = $request->query->get('perPage', 2);
        $page         = $request->query->get('page', 1);
        $categoryIds  = $request->query->get('categoryIds');

        if ($categoryIds) {
            $queryBuilder = $feedRepository->createFindByCategoryIdsQueryBuilder($categoryIds);
        } else {
            $queryBuilder = $feedRepository->createFindAllQueryBuilder();
        }

        $pager      = $paginationFactory->create($queryBuilder, $itemsPerPage, $page);
        $categories = $categoryRepository->findAll();

        return $this->render('@App/feed/index.html.twig', [
            'categories'          => $categories,
            'selectedCategoryIds' => $categoryIds,
            'feeds'               => $pager->getCurrentPageResults(),
            'pagination'          => $pager,
        ]);
    }

    public function createAction(Request $request, EntityManagerInterface $entityManager)
    {
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($feed);
            $entityManager->flush();

            $this->addFlash('success', 'Feed created successfully!');

            return $this->redirectToRoute('app.feed.edit', [
                'id' => $feed->getId(),
            ]);
        }

        return $this->render('@App/feed/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction(Feed $feed, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Feed updated successfully!');

            return $this->redirectToRoute('app.feed.edit', [
                'id' => $feed->getId(),
            ]);
        }

        return $this->render('@App/feed/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction(Feed $feed, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($feed);
        $entityManager->flush();

        $this->addFlash('success', 'Deleted feed successfully!');

        return $this->redirectToRoute('app.feed.list');
    }
}
