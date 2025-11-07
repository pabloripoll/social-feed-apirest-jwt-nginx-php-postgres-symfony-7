<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Member\Repository\MemberProfileRepository;

#[ORM\Entity(repositoryClass: MemberProfileRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members_profile")]
#[ORM\UniqueConstraint(name: "uniq_members_profile_nickname", columns: ["nickname"])]
class MemberProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    #[ORM\Column(type: "string", length: 32, unique: true)]
    private string $nickname;

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;
        return $this;
    }

    #[ORM\Column(type: "text", length: 32)]
    private string $avatar;

    public function getAvatar(): string|null
    {
        return $this->avatar ?? null;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;
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
