<?php

namespace App\Domain\Geo\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Geo\Entity\GeoContinent;
use App\Domain\Geo\Repository\GeoRegionRepository;

#[ORM\Entity(repositoryClass: GeoRegionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "geo_regions")]
class GeoRegion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: GeoContinent::class, inversedBy: 'regions')]
    #[ORM\JoinColumn(name: "continent_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private GeoContinent $continent;

    public function getContinent(): GeoContinent
    {
        return $this->continent;
    }

    public function setContinent(GeoContinent $continent): self
    {
        $this->continent = $continent;
        return $this;
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
     * --- Hooks ---
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
