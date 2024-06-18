<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $postcode = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
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
     * @return string|null
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * 
     * @return void
     */
    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * @return Meter|null
     */
    public function getMeter(): ?Meter
    {
        return $this->meter;
    }

    /**
     * @param Meter $meter
     * 
     * @return void
     */
    public function setMeter(Meter $meter): void
    {
        $this->meter = $meter;
    }
}
