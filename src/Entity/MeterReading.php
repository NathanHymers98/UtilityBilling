<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MeterReadingRepository;

#[ORM\Entity(repositoryClass: MeterReadingRepository::class)]
class MeterReading
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column]
    private ?int $reading = null;

    #[ORM\ManyToOne(inversedBy: 'meterReadings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meter $meter = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return \DateTimeInterface|null
     */
    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }
    
    /**
     * @param  \DateTimeInterface $timestamp
     * @return void
     */
    public function setTimestamp(\DateTimeInterface $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int|null
     */
    public function getReading(): ?int
    {
        return $this->reading;
    }

    /**
     * @param int $reading
     * 
     * @return void
     */
    public function setReading(int $reading): void
    {
        $this->reading = $reading;
    }

    public function getMeter(): ?Meter
    {
        return $this->meter;
    }

    public function setMeter(?Meter $meter): static
    {
        $this->meter = $meter;

        return $this;
    }
}
