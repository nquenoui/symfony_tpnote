<?php

namespace App\Controller;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, Request $request)
    {
        return $this->render('home/index.html.twig', [
                'LessPriceProducts' => $productRepository->findLessPrice(5),
                'MostRecentProducts' => $productRepository->findMostRecent(5)
        ]);
    }
}
