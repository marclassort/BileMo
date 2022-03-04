<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["product"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom du produit doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom du produit ne doit pas faire plus de {{ limit }} caractères.'
    )]
    #[Groups(["product"])]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups(["product"])]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product"])]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["product"])]
    private $constructor;

    #[ORM\Column(type: 'float')]
    #[Groups(["product"])]
    private $priceExcludingTaxes;

    #[ORM\Column(type: 'float')]
    #[Groups(["product"])]
    private $VAT;

    #[ORM\Column(type: 'integer')]
    #[Groups(["product"])]
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getConstructor(): ?string
    {
        return $this->constructor;
    }

    public function setConstructor(string $constructor): self
    {
        $this->constructor = $constructor;

        return $this;
    }

    public function getPriceExcludingTaxes(): ?float
    {
        return $this->priceExcludingTaxes;
    }

    public function setPriceExcludingTaxes(float $priceExcludingTaxes): self
    {
        $this->priceExcludingTaxes = $priceExcludingTaxes;

        return $this;
    }

    public function getVAT(): ?float
    {
        return $this->VAT;
    }

    public function setVAT(float $VAT): self
    {
        $this->VAT = $VAT;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
