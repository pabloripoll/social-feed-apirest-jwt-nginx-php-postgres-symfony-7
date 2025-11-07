<?php

namespace App\Domain\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Admin\Repository\AdminRepository;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "admins")]
#[ORM\UniqueConstraint(name: "uniq_admins_uid", columns: ["uid"])]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "bigint", unique: true, nullable: false)]
    private int $uid;

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: GeoRegion::class)]
    #[ORM\JoinColumn(name: "region_id", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    private GeoRegion $region_id;

    public function getRegion(): GeoRegion
    {
        return $this->region_id;
    }

    public function setRegion(GeoRegion $region_id): self
    {
        $this->region_id = $region_id;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_active = false;

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_banned = false;

    public function getIsBenned(): bool
    {
        return $this->is_banned;
    }

    public function setIsBenned(bool $is_banned): self
    {
        $this->is_banned = $is_banned;
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

        // Set a random 6-digit UID if not already set
        if (empty($this->uid)) {
            $this->uid = random_int(100000, 999999);
        }

        $this->created_at = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updated_at = new \DateTime();
    }
}
