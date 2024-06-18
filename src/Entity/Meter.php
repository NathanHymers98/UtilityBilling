<?php

namespace App\Entity;

use App\Repository\MeterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeterRepository::class)]
class Meter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, MeterReading>
     */
    #[ORM\OneToMany(targetEntity: MeterReading::class, mappedBy: 'meter')]
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
     * @return Collection<int, MeterReading>
     */
    public function getMeterReadings(): Collection
    {
        return $this->meterReadings;
    }

    public function addMeterReading(MeterReading $meterReading): static
    {
        if (!$this->meterReadings->contains($meterReading)) {
            $this->meterReadings->add($meterReading);
            $meterReading->setMeter($this);
        }

        return $this;
    }

    public function removeMeterReading(MeterReading $meterReading): static
    {
        if ($this->meterReadings->removeElement($meterReading)) {
            // set the owning side to null (unless already changed)
            if ($meterReading->getMeter() === $this) {
                $meterReading->setMeter(null);
            }
        }

        return $this;
    }
}
