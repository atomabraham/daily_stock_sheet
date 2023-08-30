<?php

namespace App\Controller;

use App\Entity\StockRecord;
use App\Repository\StockRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockRecordController extends AbstractController
{
    /**
     * Affichage du formulaire pour choisir la date
    */
    
    #[Route('/StockSheet', name: 'stocksheet')]
    public function index(): Response
    {
        return $this->render('stock_record/index.html.twig', [
            'controller_name' => 'StockRecordController',
        ]);
    }
    
    /**
     * Affichage des fiches de stock
    */

    public function list(Request $request, StockRecordRepository $stockRecordRepository,EntityManagerInterface $entityManager): Response
    {
        // Récupérer la date choisie par l'utilisateur depuis la requête
        $dateChoisie = $request->query->get('date');

        // Convertir la date en objet DateTime
        $date = new \DateTime($dateChoisie);

        // Récupérer les fiches de stock à partir de la date choisie
        $fichesStock = $entityManager->getRepository(StockRecord::class)->findByDate($date);
        
        if($fichesStock){

            $stockRecord = $fichesStock[0];
            $products = $stockRecord -> getStock();

        }else{
            $products = "ok";
        }
        // Afficher les fiches de stock dans un template
        return $this->render('stock_record/list.html.twig', [
            'date' => $dateChoisie,
            'products' => $products,
        ]);

    }
}
