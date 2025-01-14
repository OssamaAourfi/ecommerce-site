<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home' ,methods: ['GET'])]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    #[Route('/home/product/{id}/show', name: 'app_home_product_show' ,methods: ['GET'])]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        $lastProducts = $productRepository->findBy([],['id'=>'DESC'],5);
        return $this->render('home/show.html.twig', [
            'product' => $product,
            'products' => $lastProducts,
        ]);
    }

    #[Route('/home/product/{id}/filter', name: 'app_home_product_filter' ,methods: ['GET'])]
    public function filter( Category $category,
                            ProductRepository $productRepository,
                            CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findBy(['category' => $category]);
        return $this->render('home/filter.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
            'selectedCategory' => $category->getId()
        ]);
    }

}
