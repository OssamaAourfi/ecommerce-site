<?php

namespace App\Controller;

use App\Entity\AddProductHistory;
use App\Form\AddProductHistoryType;
use App\Repository\AddProductHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/add/product/history')]
final class AddProductHistoryController extends AbstractController
{
    #[Route(name: 'app_add_product_history_index', methods: ['GET'])]
    public function index(AddProductHistoryRepository $addProductHistoryRepository): Response
    {
        return $this->render('add_product_history/index.html.twig', [
            'add_product_histories' => $addProductHistoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_add_product_history_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $addProductHistory = new AddProductHistory();
        $form = $this->createForm(AddProductHistoryType::class, $addProductHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($addProductHistory);
            $entityManager->flush();

            return $this->redirectToRoute('app_add_product_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('add_product_history/new.html.twig', [
            'add_product_history' => $addProductHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_add_product_history_show', methods: ['GET'])]
    public function show(AddProductHistory $addProductHistory): Response
    {
        return $this->render('add_product_history/show.html.twig', [
            'add_product_history' => $addProductHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_add_product_history_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AddProductHistory $addProductHistory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddProductHistoryType::class, $addProductHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_add_product_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('add_product_history/edit.html.twig', [
            'add_product_history' => $addProductHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_add_product_history_delete', methods: ['POST'])]
    public function delete(Request $request, AddProductHistory $addProductHistory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$addProductHistory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($addProductHistory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_add_product_history_index', [], Response::HTTP_SEE_OTHER);
    }
}
