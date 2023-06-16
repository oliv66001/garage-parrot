<?php

namespace App\Entity;

use App\Repository\RepairRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepairRepository::class)]
class Repair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(targetEntity:"App\Entity\CategoryRepair", inversedBy:"repairs")]
    #[ORM\JoinColumn(nullable: false)]
    private $category = null;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return ?CategoryRepair
     */
    public function getCategory(): ?CategoryRepair
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param ?CategoryRepair $category
     *
     * @return self
     */
    public function setCategory(?CategoryRepair $category): self
    {
        $this->category = $category;

        return $this;
    }
}
