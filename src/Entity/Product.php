<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /**
     * @Groups({"group1"})
     */
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    /**
     * @Groups({"group1"})
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    private $constructor;

    #[ORM\Column(type: 'float')]
    private $priceExcludingTaxes;

    #[ORM\Column(type: 'float')]
    private $VAT;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToMany(targetEntity: Customer::class, inversedBy: 'products')]
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        $this->customers->removeElement($customer);

        return $this;
    }
}
