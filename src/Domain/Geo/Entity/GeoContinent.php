<?php

namespace App\Domain\Geo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Domain\Geo\Repository\GeoContinentRepository;

#[ORM\Entity(repositoryClass: GeoContinentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "geo_continents")]
#[ORM\UniqueConstraint(name: "uniq_geo_continents_name", columns: ["name"])]
class GeoContinent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "string", length: 64)]
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updated_at;

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * One continent has many regions.
     * mappedBy refers to the property name on GeoRegion (see GeoRegion::$continent).
     */
    #[ORM\OneToMany(mappedBy: 'continent', targetEntity: GeoRegion::class, cascade: ['persist'], orphanRemoval: false)]
    private Collection $regions;

    /**
     * Regions collection accessors
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    /**
     * Hooks
     */

    #[ORM\PrePersist]
    public function setOnCreateEntity(): void {
        $this->created_at = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updated_at = new \DateTime();
    }
}
