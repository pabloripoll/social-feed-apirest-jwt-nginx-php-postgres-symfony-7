<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Member\Repository\MemberActivationCodeRepository;

#[ORM\Entity(repositoryClass: MemberActivationCodeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members_activation_codes")]
#[ORM\UniqueConstraint(name: "uniq_members_activation_codes_code", columns: ["code"])]
class MemberActivationCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: "string", length: 16)]
    private ?string $code = null;

    public function generateCode(): void
    {
        $this->code = strtoupper(bin2hex(random_bytes(4))); // Example: "A1B2C3D4"
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
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
        if (empty($this->code)) {
            $this->generateCode();
        }

        $this->created_at = new \DateTime();
        $this->setOnUpdateEntity();
    }

    #[ORM\PreUpdate]
    public function setOnUpdateEntity(): void {
        $this->updated_at = new \DateTime();
    }
}
