<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

     
    #[ORM\ManyToOne(targetEntity:"App\Entity\Vehicle", inversedBy:"images")]
    #[ORM\JoinColumn(nullable:true)]
    private $vehicle;

    #[ORM\OneToOne(targetEntity:"App\Entity\CategoryRepair", inversedBy:"image", cascade:["persist", "remove"])]
    #[ORM\JoinColumn(nullable:true)]
    private $categoryRepair;

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

    /**
     * Get the value of vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set the value of vehicle
     */
    public function setVehicle($vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }


    public function getCategoryRepair(): ?CategoryRepair
{
    return $this->categoryRepair;
}

public function setCategoryRepair(?CategoryRepair $categoryRepair): self
{
    $this->categoryRepair = $categoryRepair;

    return $this;
}

  
}
