<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Continue_;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class VenteController extends AbstractController

{
    // affichage de tous les produits avec options d'achat

    #[Route('/AchatProduit', name: 'AchatProduit')]
    public function AchatProduit(ProductsRepository $productsRepository): Response 
    {
        // $products = $this->getDoctrine()->getRepository(Products::class)->findAll();

        return $this->render('vente/chooseProduct.html.twig',[
            'products' => $productsRepository->findAll(),
        ]);
    }

    // affichage de l'historique de ventes
    #[Route('/vente', name: 'app_vente')]
    public function listSales(EntityManagerInterface $entityManager): Response
    {
        $sales = $entityManager->getRepository(Sale::class)->findAll();

        return $this->render('vente/index.html.twig', [
            'sales' => $sales,
        ]);
    }

     /**
     * @ORM\Column(type="integer")
     */
    private $product_id;

    // ...

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function setProductId(int $productId): void
    {
        $this->product_id = $productId;
    }


    /**
     * @Route("/produits/{id}/acheter", name="acheter_produit")
     */
    public function acheterProduit(Request $request, Products $product,  EntityManagerInterface $entityManager,  SessionInterface $session) : Response
    {
        $productContent = $entityManager->getRepository(Products::class)->find($product);
        // Logique d'achat du produit

        
        $productStockFinal = $productContent -> getStockFinal();

        if($productStockFinal>0){
            $vente = new Sale();

            $vente->setProduct($product);
            $vente->setDate(new \DateTime());
            $vente->setQuantity(1);
    
            $entityManager->persist($vente);
            $entityManager->flush();
    
            // mise a jour du stock final d'un  produit
            $productContent = $entityManager->getRepository(Products::class)->find($product);
    
            $newStockFinal = $product->getStockFinal() - 1;
            $product->setStockFinal($newStockFinal);
    
            $entityManager->persist($product);
            $entityManager->flush();

            return new RedirectResponse($this->generateUrl('AchatProduit'));
        }else{
            
            return new RedirectResponse($this->generateUrl('Acceuil'));
        }



        // Ajouter un message flash pour indiquer que l'achat a réussi
        // $session->getFlashBag()->add('success', 'L\'achat a été effectué avec succès !');
        
    }

}
