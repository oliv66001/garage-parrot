<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Vehicle::class)]
    private Collection $gender;

    public function __construct()
    {
        $this->gender = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getGender(): Collection
    {
        return $this->gender;
    }

    public function addGender(Vehicle $gender): self
    {
        if (!$this->gender->contains($gender)) {
            $this->gender->add($gender);
            $gender->setCategorie($this);
        }

        return $this;
    }

    public function removeGender(Vehicle $gender): self
    {
        if ($this->gender->removeElement($gender)) {
            // set the owning side to null (unless already changed)
            if ($gender->getCategorie() === $this) {
                $gender->setCategorie(null);
            }
        }

        return $this;
    }
}
