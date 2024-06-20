<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, MeterReading>
     */
    #[ORM\OneToMany(targetEntity: MeterReading::class, mappedBy: 'house')]
    private Collection $meterReadings;

    public function __construct()
    {
        $this->meterReadings = new ArrayCollection();
    }

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
     * @return Collection<int, MeterReading>
     */
    public function getMeterReadings(): Collection
    {
        return $this->meterReadings;
    }

    /**
     * @param MeterReading $meterReading
     * 
     * @return void
     */
    public function addMeterReading(MeterReading $meterReading): void
    {
        if (!$this->meterReadings->contains($meterReading)) {
            $this->meterReadings->add($meterReading);
            $meterReading->setHouse($this);
        }
    }

    /**
     * @param MeterReading $meterReading
     * 
     * @return void
     */
    public function removeMeterReading(MeterReading $meterReading): void
    {
        if ($this->meterReadings->removeElement($meterReading)) {
            // set the owning side to null (unless already changed)
            if ($meterReading->getHouse() === $this) {
                $meterReading->setHouse(null);
            }
        }
    }
}
