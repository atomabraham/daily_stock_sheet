<?php

namespace App\Controller;

use App\Entity\Deliveries;
use App\Entity\Products;
use App\Form\DeliveryType;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use DateTime;
use DateTimeInterface;
use App\Services\StockRecordService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    /**
        *Affichage de la liste de tous les produits
    */

    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    private $entityManager;
    private $stockRecordService;

    public function __construct(EntityManagerInterface $entityManager, StockRecordService $stockRecordService)
    {
        $this->entityManager = $entityManager;
        $this->stockRecordService = $stockRecordService;
    }
    /**
        *Creation d'un produit
    */

    

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product, [
            'isEdit' => true,'isEditCreate' => false
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockInitial = $form -> get('stockI') -> getData();
            $stockLivrer = $form -> get('stockLivrer') -> getData();

            $product -> setStockTotal($stockInitial + $stockLivrer);
            $product -> setStockFinal($stockInitial + $stockLivrer);

            $entityManager->persist($product);
            $entityManager->flush();

            //mise a jour de la fiche de stock
            $this->stockRecordService->createStockRecordForToday();
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
        *Affichage d'un produit
    */

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
        *Modification d'un produit
    */

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product, [
            'isEdit' => true,'isEditCreate' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
        *Suppression d'un produit
    */

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
        *Livraison de produits
    */

    /**
     * @Route("/deliveries/{id}/quantity", name="delivery_quantity")
    */

    // private $dateTime;
    

    

    public function delivery(Request $request, Products $product,EntityManagerInterface $entityManager, ProductsRepository $productsRepository) : Response 
    {
        $delivery = new Deliveries();
        $form = $this -> createForm(DeliveryType::class, $delivery);
        $form -> handleRequest($request);

        //les information de produit a modifier lors de la livraison
        $stockInitial = $product -> getStockI();
        $stockFinal = $product -> getStockFinal();

        if ($form->isSubmitted() && $form->isValid()) {

            // recuperation des donnees du formulaire
            $quantity = $form->getData()->getQuantity();

            // recuperation des donnees pour verifier si un produit a deja ete livrer le meme jour
            $dateLivraison = new \DateTime();
            $produitId = $product -> getId();

            // recuperation du reposity des livraisons
            $livraisonRepository = $entityManager->getRepository(Deliveries::class);
            $products = $entityManager->getRepository(Products::class);

            // verifier si une livraison existente correspond au produit et a la date de livraison
            $livraisonExistante = $livraisonRepository->findOneBy([
                'product' => $produitId,
                'date' => $dateLivraison,
            ]);

            
            if($livraisonExistante){
                // recuperation du produit concerner
                $productIndex = $products -> findOneBy(
                    [
                        'id' => $produitId,
                    ]
                );

                // Mise a jour du stockLivrer si un produit a deja ete livrer le meme jour
                $productIndex -> setStockLivrer($productIndex -> getStockLivrer() + $quantity);

                $product->setStockTotal($quantity + $stockInitial);
                $product->setStockFinal($quantity + $stockFinal);
                
                //Enregistrement des information de livraison
                $delivery->setProduct($product);

                $delivery->setDate(new DateTime());

                $this->entityManager->persist($delivery);
                $this->entityManager->flush();

                //mise a jour de la fiche de stock
                $this->stockRecordService->createStockRecordForToday();

                return $this->redirectToRoute('LivrerProduit'); // Redirigez vers une page de succès après traitement du formulaire
            }else{

                //Enregistrement des information de livraison
                $product->setStockLivrer($quantity);
                $product->setStockTotal($quantity + $stockInitial);
                $product->setStockFinal($quantity + $stockFinal);

                $delivery->setProduct($product);

                $delivery->setDate(new DateTime());

                // $entityManager = $this->$entityManager->getRepository(Products::class)->findAll();
                $this->entityManager->persist($delivery);
                $this->entityManager->flush();

                //mise a jour de la fiche de stock
                $this->stockRecordService->createStockRecordForToday();

                return $this->redirectToRoute('LivrerProduit'); // Redirigez vers une page de succès après traitement du formulaire
            }
            
        }

        // affichage du formulaire pour entrer la quantité à livrer
        return $this->render('delivery/quantity.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);


    }

}
