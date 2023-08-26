<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $prixV = null;

    #[ORM\Column]
    private ?int $stockI = null;

    #[ORM\Column]
    private ?int $stockLivrer = null;

    #[ORM\Column]
    private ?int $stockTotal = null;

    #[ORM\Column]
    private ?int $stockFinal = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Sales::class)]
    private Collection $sales;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Deliveries::class)]
    private Collection $deliveries;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Sales::class)]
    private Collection $ventes;

    public function __construct()
    {
        $this->sales = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrixV(): ?int
    {
        return $this->prixV;
    }

    public function setPrixV(int $prixV): static
    {
        $this->prixV = $prixV;

        return $this;
    }

    public function getStockI(): ?int
    {
        return $this->stockI;
    }

    public function setStockI(int $stockI): static
    {
        $this->stockI = $stockI;

        return $this;
    }

    public function getStockLivrer(): ?int
    {
        return $this->stockLivrer;
    }

    public function setStockLivrer(int $stockLivrer): static
    {
        $this->stockLivrer = $stockLivrer;

        return $this;
    }

    public function getStockTotal(): ?int
    {
        return $this->stockTotal;
    }

    public function setStockTotal(int $stockTotal): static
    {
        $this->stockTotal = $stockTotal;

        return $this;
    }

    public function getStockFinal(): ?int
    {
        return $this->stockFinal;
    }

    public function setStockFinal(int $stockFinal): static
    {
        $this->stockFinal = $stockFinal;

        return $this;
    }

    /**
     * @return Collection<int, Sales>
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sales $sale): static
    {
        if (!$this->sales->contains($sale)) {
            $this->sales->add($sale);
            $sale->setProduct($this);
        }

        return $this;
    }

    public function removeSale(Sales $sale): static
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getProduct() === $this) {
                $sale->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Deliveries>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Deliveries $delivery): static
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries->add($delivery);
            $delivery->setProduct($this);
        }

        return $this;
    }

    public function removeDelivery(Deliveries $delivery): static
    {
        if ($this->deliveries->removeElement($delivery)) {
            // set the owning side to null (unless already changed)
            if ($delivery->getProduct() === $this) {
                $delivery->setProduct(null);
            }
        }

        return $this;
    }
    
}
