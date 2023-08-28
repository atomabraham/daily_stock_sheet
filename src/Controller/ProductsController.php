<?php

namespace App\Controller;

use App\Entity\Deliveries;
use App\Entity\Products;
use App\Form\DeliveryType;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product, [
            'isEdit' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockInitial = $form -> get('stockI') -> getData();
            $stockLivrer = $form -> get('stockLivrer') -> getData();

            $product -> setStockTotal($stockInitial + $stockLivrer);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductsType::class, $product, [
            'isEdit' => true,
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

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }

    //livraison d'un produit
    /**
     * @Route("/deliveries/{id}/quantity", name="delivery_quantity")
    */

    private $entityManager;
    private $dateTime;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    

    public function delivery(Request $request, Products $product,EntityManagerInterface $entityManager) : Response 
    {
        $delivery = new Deliveries();
        $form = $this -> createForm(DeliveryType::class, $delivery);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->getData()->getQuantity();

            // Enregistrez la quantité dans le champ stockLivrer du produit
            $stockInitial = $product -> getStockI();
            $stockFinal = $product -> getStockFinal();

            $product->setStockLivrer($quantity);
            $product->setStockTotal($quantity + $stockInitial);
            $product->setStockFinal($quantity + $stockFinal);

            $delivery->setProduct($product);

            $delivery->setDate(new DateTime());

            // $entityManager = $this->$entityManager->getRepository(Products::class)->findAll();
            $this->entityManager->persist($delivery);
            $this->entityManager->flush();


            return $this->redirectToRoute('Acceuil'); // Redirigez vers une page de succès après traitement du formulaire
        }

        return $this->render('delivery/quantity.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);


    }

}
