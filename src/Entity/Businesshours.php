<?php

namespace App\Entity;

use App\Repository\BusinessHoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusinessHoursRepository::class)]
class BusinessHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $day = null;

    #[ORM\Column(nullable: true)]
    private ?string $openTimeMorning = null;

    #[ORM\Column(nullable: true)]
    private ?string $closedTimeMorning = null;

    #[ORM\Column(nullable: true)]
    private ?string $openTimeAfternoon = null;

    #[ORM\Column(nullable: true)]
    private ?string $closedTimeAfternoon = null;

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


    /**
     * Get the value of openTimeMorning
     *
     * @return ?string
     */
    public function getOpenTimeMorning(): ?string
    {
        return $this->openTimeMorning;
    }

    /**
     * Set the value of openTimeMorning
     *
     * @param ?string $openTimeMorning
     *
     * @return self
     */
    public function setOpenTimeMorning(?string $openTimeMorning): self
    {
        $this->openTimeMorning = $openTimeMorning;

        return $this;
    }

    /**
     * Get the value of closedTimeMorning
     *
     * @return ?string
     */
    public function getClosedTimeMorning(): ?string
    {
        return $this->closedTimeMorning;
    }

    /**
     * Set the value of closedTimeMorning
     *
     * @param ?string $closedTimeMorning
     *
     * @return self
     */
    public function setClosedTimeMorning(?string $closedTimeMorning): self
    {
        $this->closedTimeMorning = $closedTimeMorning;

        return $this;
    }

    /**
     * Get the value of openTimeAfternoon
     *
     * @return ?string
     */
    public function getOpenTimeAfternoon(): ?string
    {
        return $this->openTimeAfternoon;
    }

    /**
     * Set the value of openTimeAfternoon
     *
     * @param ?string $openTimeAfternoon
     *
     * @return self
     */
    public function setOpenTimeAfternoon(?string $openTimeAfternoon): self
    {
        $this->openTimeAfternoon = $openTimeAfternoon;

        return $this;
    }

    /**
     * Get the value of closedTimeAfternoon
     *
     * @return ?string
     */
    public function getClosedTimeAfternoon(): ?string
    {
        return $this->closedTimeAfternoon;
    }

    /**
     * Set the value of closedTimeAfternoon
     *
     * @param ?string $closedTimeAfternoon
     *
     * @return self
     */
    public function setClosedTimeAfternoon(?string $closedTimeAfternoon): self
    {
        $this->closedTimeAfternoon = $closedTimeAfternoon;

        return $this;
    }
}
