<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatgeoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('catgeory/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'category_new')]
    public function addCategory(EntityManagerInterface $em,Request $request): Response{
        $category = new Category();
        $form = $this->CreateForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('catgeory/new.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/category/{id}/edit', name: 'category_edit')]
    public function updateCategory(EntityManagerInterface $em,Request $request,Category $category): Response{
        $form = $this->CreateForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_category');
        }
        return $this->render('catgeory/edit.html.twig', ['form'=>$form->createView()]);
    }
    #[Route('/category/{id}/delete', name: 'category_delete')]
public function deleteCategory(EntityManagerInterface $em,Category $category): Response{
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('app_category');
    }
}
