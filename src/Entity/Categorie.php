<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\MyTrait\SlugTrait;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Categorie
{

    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Vehicle::class)]
    private Collection $vehicleType;

    public function __construct()
    {
        $this->vehicleType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   /**
 * @return Collection<int, Vehicle>
 */
public function getVehicleType(): Collection
{
    return $this->vehicleType;
}

public function addVehicleType(Vehicle $vehicleType): self
{
    if (!$this->vehicleType->contains($vehicleType)) {
        $this->vehicleType->add($vehicleType);
        $vehicleType->setCategorie($this);
    }

    return $this;
}

public function removeVehicleType(Vehicle $vehicleType): self
{
    if ($this->vehicleType->removeElement($vehicleType)) {
        // set the owning side to null (unless already changed)
        if ($vehicleType->getCategorie() === $this) {
            $vehicleType->setCategorie(null);
        }
    }

    return $this;
}


    /**
     * Get the value of name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

}
