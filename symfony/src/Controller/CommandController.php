<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Command;
use App\Entity\Product;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     */
    public function index(CommandRepository $commandRepository, Request $request): Response
    {
        $commands = $commandRepository->findAll();
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'commands' => $commands
        ]);
    }

    /**
     * @Route("/command/{id}", methods={"GET"}, name="command.show")
     */
    public function show(int $id,ProductRepository $productRepository,CommandRepository $commandRepository): Response{
        $command = $commandRepository->find($id);
        $products = $command->getProducts();
        $totalPrice = 0;
        foreach($products as $product){
            if (!empty($product->getPrice())){
                $totalPrice+= $product->getPrice();
            }
        }
        return $this->render('command/show.html.twig', [
            'controller_name' => 'CommandController',
            'command' => $command,
            'products' => $products,
            'totalPrice' => $totalPrice
        ]);
    }

}
