<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\MyTrait\SlugTrait;
use App\Repository\VehicleRepository;

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

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $kilometer = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\OneToOne(mappedBy: 'subject', cascade: ['persist', 'remove'])]
    private ?Contact $contact = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleType')]
    private ?Categorie $categorie = null;

    #[ORM\Column(type:"date")]
    private ?DateTimeInterface $year = null;

    
    public function __construct()
    {
        $this->year = new \DateTime(date('Y-m-d'));
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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        // unset the owning side of the relation if necessary
        if ($contact === null && $this->contact !== null) {
            $this->contact->setSubject(null);
        }

        // set the owning side of the relation if necessary
        if ($contact !== null && $contact->getSubject() !== $this) {
            $contact->setSubject($this);
        }

        $this->contact = $contact;

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
}
