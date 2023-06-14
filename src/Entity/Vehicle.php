<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\MyTrait\SlugTrait;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{

    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $brand = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $kilometer = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'subject')]
    private $contacts;

    #[ORM\ManyToOne(inversedBy: 'vehicleType')]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: "date")]
    private ?DateTimeInterface $year = null;

    #[ORM\OneToMany(targetEntity: "App\Entity\Image", mappedBy: "vehicle", cascade: ["persist"])]
    private $images;

    #[ORM\Column(length: 255, nullable: false)]
    private ?bool $displayOnHomePage = false;

    public function __construct()
    {
        $this->year = new \DateTime(date('Y-m-d'));
        $this->images = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getKilometer(): ?int
    {
        return $this->kilometer;
    }

    public function setKilometer(int $kilometer): self
    {
        $this->kilometer = $kilometer;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


    /**
     * Get the value of year
     *
     * @return ?DateTimeInterface
     */
    public function getYear(): ?DateTimeInterface
    {
        return $this->year;
    }

    /**
     * Set the value of year
     *
     * @param ?DateTimeInterface $year
     *
     * @return self
     */
    public function setYear(?DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setVehicle($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVehicle() === $this) {
                $image->setVehicle(null);
            }
        }

        return $this;
    }


    /**
     * Get the value of displayOnHomePage
     *
     * @return ?bool
     */
    public function getDisplayOnHomePage(): ?bool
    {
        return $this->displayOnHomePage;
    }

    /**
     * Set the value of displayOnHomePage
     *
     * @param ?bool $displayOnHomePage
     *
     * @return self
     */
    public function setDisplayOnHomePage(?bool $displayOnHomePage): self
    {
        $this->displayOnHomePage = $displayOnHomePage;

        return $this;
    }

   
}
