<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: "App\Repository\CategoryRepairRepository")]

class CategoryRepair
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;


    #[ORM\Column(type: "string", length: 255)]
    private $name;


    #[ORM\OneToMany(targetEntity: "App\Entity\Repair", mappedBy: "category")]
    private $repairs;

    #[ORM\OneToOne(targetEntity: "App\Entity\Image", mappedBy: "categoryRepair", cascade: ["persist"])]
    private $image;

    public function __construct()
    {
        $this->repairs = new ArrayCollection();
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

    /**
     * @return Collection|Repair[]
     */
    public function getRepairs(): Collection
    {
        return $this->repairs;
    }

    public function addRepair(Repair $repair): self
    {
        if (!$this->repairs->contains($repair)) {
            $this->repairs[] = $repair;
            $repair->setCategory($this);
        }

        return $this;
    }

    public function removeRepair(Repair $repair): self
    {
        if ($this->repairs->contains($repair)) {
            $this->repairs->removeElement($repair);
            // set the owning side to null (unless already changed)
            if ($repair->getCategory() === $this) {
                $repair->setCategory(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }
    
    public function setImage(?Image $image): self
    {
        $this->image = $image;
    
        // Important: assurez-vous d'avoir cette ligne
        if ($image !== null) {
            $image->setCategoryRepair($this);
        }
    
        return $this;
    }
}
