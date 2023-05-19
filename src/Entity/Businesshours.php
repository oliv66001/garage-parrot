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
    private ?int $openTimeMorning = null;

    #[ORM\Column]
    private ?int $closedTimeMorning = null;

    #[ORM\Column]
    private ?int $openTimeAfternoon = null;

    #[ORM\Column]
    private ?int $closedTimeAfternoon = null;

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
     * @return ?int
     */
    public function getOpenTimeMorning(): ?int
    {
        return $this->openTimeMorning;
    }

    /**
     * Set the value of openTimeMorning
     *
     * @param ?int $openTimeMorning
     *
     * @return self
     */
    public function setOpenTimeMorning(?int $openTimeMorning): self
    {
        $this->openTimeMorning = $openTimeMorning;

        return $this;
    }

    /**
     * Get the value of closedTimeMorning
     *
     * @return ?int
     */
    public function getClosedTimeMorning(): ?int
    {
        return $this->closedTimeMorning;
    }

    /**
     * Set the value of closedTimeMorning
     *
     * @param ?int $closedTimeMorning
     *
     * @return self
     */
    public function setClosedTimeMorning(?int $closedTimeMorning): self
    {
        $this->closedTimeMorning = $closedTimeMorning;

        return $this;
    }

    /**
     * Get the value of openTimeAfternoon
     *
     * @return ?int
     */
    public function getOpenTimeAfternoon(): ?int
    {
        return $this->openTimeAfternoon;
    }

    /**
     * Set the value of openTimeAfternoon
     *
     * @param ?int $openTimeAfternoon
     *
     * @return self
     */
    public function setOpenTimeAfternoon(?int $openTimeAfternoon): self
    {
        $this->openTimeAfternoon = $openTimeAfternoon;

        return $this;
    }

    /**
     * Get the value of closedTimeAfternoon
     *
     * @return ?int
     */
    public function getClosedTimeAfternoon(): ?int
    {
        return $this->closedTimeAfternoon;
    }

    /**
     * Set the value of closedTimeAfternoon
     *
     * @param ?int $closedTimeAfternoon
     *
     * @return self
     */
    public function setClosedTimeAfternoon(?int $closedTimeAfternoon): self
    {
        $this->closedTimeAfternoon = $closedTimeAfternoon;

        return $this;
    }
}
