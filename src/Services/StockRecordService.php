<?php

namespace App\Services;

use App\Entity\Products;
use App\Entity\StockRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Service;

#[Service]

// service qui créé automatiquement les fiches de stocks
class StockRecordService 
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createStockRecordForToday(): void
    {
        $today = new \DateTime();
        
        
        $products = $this->entityManager->getRepository(Products::class)->findAll();

        // Récupérez les stocks finaux de la journée précédente
        $previousDate = (new \DateTime())->modify('-1 day');
        $previousStockRecord = $this->entityManager->getRepository(StockRecord::class)->findOneBy(['date' => $previousDate]);

        if ($previousStockRecord) {
            $previousContentStockRecords = $previousStockRecord -> getStock();

            
            $stockData = [];
            $i=-1;

            foreach ($products as $product) {
                $i++;
                $productName = $product -> getName();
                $stockI = $product -> getStockI();
                $stockFinal = $product -> getStockFinal();
                $stockTotal = $product -> getStockTotal();
                
                
                // Utilisez le stockFinal de la journée précédente s'il existe
                foreach ($previousContentStockRecords as $previousContentStockRecord){
                    if($previousContentStockRecord){
                        if($previousContentStockRecord['product'] == $productName){
                            $stockInitial = $previousContentStockRecord['stockFinal']; 
                        }
                    }else{
                        $stockInitial = $stockI; 
                    }
                }

                $stockData[] = [
                    'product' => $productName,
                    'prixV' => $product->getPrixV(),
                    'stockI' => $stockInitial,
                    'stockLivrer' => $product->getStockLivrer(),
                    'stockFinal' => $product->getStockFinal(),
                    'stockVendu' => ($stockTotal - $stockFinal),
                ];
            }

            // print_r($stockData);
            // echo($previousContentStockRecord);
            
            $existingRecord = $this->entityManager->getRepository(StockRecord::class)->findOneBy(['date' => $today]);

            if($existingRecord) {
                $stockRecord = $existingRecord -> setStock($stockData);
            }else{
                $stockRecord = new StockRecord();
                $stockRecord->setDate($today);
                $stockRecord->setStock($stockData);
            }
        
            

            $this->entityManager->persist($stockRecord);
            $this->entityManager->flush();
        }else{
            foreach ($products as $product) {
                $productName = $product -> getName();
                $stockI = $product -> getStockI();
                $stockFinal = $product -> getStockFinal();
                $stockTotal = $product -> getStockTotal();
                
                
                // Utilisez le stockFinal de la journée précédente s'il existe
                
                $stockInitial = $stockI; 
                
                $stockData[] = [
                    'product' => $productName,
                    'prixV' => $product->getPrixV(),
                    'stockI' => $stockInitial,
                    'stockLivrer' => $product->getStockLivrer(),
                    'stockFinal' => $product->getStockFinal(),
                    'stockVendu' => ($stockTotal - $stockFinal),
                ];
            }

            // print_r($stockData);
            // echo($previousContentStockRecord);
            
            $existingRecord = $this->entityManager->getRepository(StockRecord::class)->findOneBy(['date' => $today]);

            if($existingRecord) {
                $stockRecord = $existingRecord -> setStock($stockData);
            }else{
                $stockRecord = new StockRecord();
                $stockRecord->setDate($today);
                $stockRecord->setStock($stockData);
            }
        
            

            $this->entityManager->persist($stockRecord);
            $this->entityManager->flush();
        }
    }
}