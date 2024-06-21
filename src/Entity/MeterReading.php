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

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $meterId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column]
    private ?int $reading = null;

    #[ORM\ManyToOne(inversedBy: 'meterReadings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?House $house = null;
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getMeterId(): ?int
    {
        return $this->meterId;
    }

    /**
     * @param int $reading
     * 
     * @return void
     */
    public function setMeterId(int $meterId): void
    {
        $this->meterId = $meterId;
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

    /**
     * @return House|null
     */
    public function getHouse(): ?House
    {
        return $this->house;
    }

    /**
     * @param House|null $house
     * 
     * @return void
     */
    public function setHouse(?House $house): void
    {
        $this->house = $house;
    }

}
