<?php

namespace App\Entity;

use App\Repository\BusinesshoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusinesshoursRepository::class)]
class Businesshours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $day = null;

    #[ORM\Column]
    private ?int $openTime = null;

    #[ORM\Column]
    private ?int $closedTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getOpenTime(): ?int
    {
        return $this->openTime;
    }

    public function setOpenTime(int $openTime): self
    {
        $this->openTime = $openTime;

        return $this;
    }

    public function getClosedTime(): ?int
    {
        return $this->closedTime;
    }

    public function setClosedTime(int $closedTime): self
    {
        $this->closedTime = $closedTime;

        return $this;
    }
}
