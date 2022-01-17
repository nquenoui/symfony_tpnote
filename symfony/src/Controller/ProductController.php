<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}", methods={"GET"}, name="product.show")
     */
    public function show(int $id,SessionInterface $session, ProductRepository $productRepository): Response{
        $cart = $session->get('panier', []);
        if(isset($cart[$id])){
            $disabledBtn = true;
        } else {
            $disabledBtn = false;
        }

        $product = $productRepository->find($id);
        if (!$product)
        {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProduitController',
            'product' => $product,
            'disabledBtn' => $disabledBtn
        ]);
    }

    
    
}
