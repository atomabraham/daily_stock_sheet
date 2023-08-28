<?php

namespace App\Controller;

use App\Entity\Deliveries;
use App\Form\DeliveryType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    // affichage de tous les produits avec options d'achat

    #[Route('/LivrerProduit', name: 'LivrerProduit')]
    public function LivrerProduit(ProductsRepository $productsRepository): Response 
    {
        // $products = $this->getDoctrine()->getRepository(Products::class)->findAll();

        return $this->render('delivery/chooseProduct.html.twig',[
            'products' => $productsRepository->findAll(),
        ]);
    }


    #[Route('/historydelivery', name: 'historydelivery')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $deliveries = $entityManager->getRepository(Deliveries::class)->findAll();

        return $this->render('delivery/index.html.twig', [
            'deliveries' => $deliveries,
        ]);
    }

    
}
