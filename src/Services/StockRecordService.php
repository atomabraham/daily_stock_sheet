<?php

namespace App\Services;

use App\Entity\Products;
use App\Entity\StockRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Service;

#[Service]
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

        $stockData = [];
        foreach ($products as $product) {
            $stockFinal = $product -> getStockFinal();
            $stockTotal = $product -> getStockTotal();
            $stockData[] = [
                'product' => $product->getName(),
                'stockI' => $product->getStockI(),
                'stockLivrer' => $product->getStockLivrer(),
                'stockFinal' => $product->getStockFinal(),
                'stockVendu' => ($stockTotal - $stockFinal),
            ];
        }

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