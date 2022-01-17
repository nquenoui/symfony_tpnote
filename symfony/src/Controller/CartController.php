<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Command;
use App\Form\CommandType;
use App\Repository\ProductRepository;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/", methods={"GET","POST"}, name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository,ManagerRegistry $doctrine, Request $request): Response
    {
        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);

        $cart = $session->get('panier', []);
        $products = [];
        $totalPrice = 0;
        foreach($cart as $id => $quantity)
        {
            $product = $productRepository->find($id);
            $products[] = array("id" => $id, "name" => $product->getName(), "price" => $product->getPrice(), "qty" => $quantity);
            $totalPrice+= $product->getPrice();
        }
        $commandForm->handleRequest($request);

        if ($commandForm->isSubmitted())
        {
            $command->setCreatedAt(new \DateTime);
            $cart = $session->get('panier', []);
            foreach($cart as $id => $quantity)
            {
                $product = $productRepository->find($id);
                $command->addProduct($product);
            }
            $entityManager = $doctrine->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($command);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
        
            $this->addFlash('success', "La commande a bien été crée !");
            $session->set('panier', array());
            return $this->redirectToRoute('cart');
            
        }
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'ProduitController',
            'products' => $products,
            'totalPrice' => $totalPrice,
            'commandForm' => $commandForm->createView() 
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", methods={"GET"}, name="cart.delete")
     */
    public function delete($id, SessionInterface $session, Request $request)
    {
        $cart = $session->get('panier', []);
        if(isset($cart[$id])){
            unset($cart[$id]);
        }
        $session->set('panier', $cart);
        $this->addFlash('success', "L'article a bien été supprimé !");
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/add/{id}", methods={"GET"}, name="cart.add")
     */
    public function add($id, SessionInterface $session, Request $request)
    {
        try{
            $cart = $session->get('panier', []);
            $cart[$id] = 1;
            $session->set('panier', $cart);
            return new Response("ok", 200);
        } catch(Exception $e){
            return new Response("nok", 400);
        }
 
    }

    
}
