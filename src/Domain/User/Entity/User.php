<?php

namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Domain\User\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "users")]
#[ORM\Index(columns: ['email'])]
#[ORM\UniqueConstraint(name: "uniq_users_email", columns: ["email"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_MEMBER = 'ROLE_MEMBER';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 32)]
    private string $role = self::ROLE_MEMBER;

    #[ORM\Column(type: "string", length: 64, unique: true)]
    private string $email;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $emailVerifiedAt = null;

    #[ORM\Column(type: "string", length: 128)]
    private string $password;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Return roles as array (Symfony expects array)
     */
    public function getRoles(): array
    {
        $role = $this->role ?? self::ROLE_MEMBER;
        // ensure ROLE_* prefix (optional if you enforce externally)
        if (strpos($role, 'ROLE_') !== 0) {
            $role = 'ROLE_' . strtoupper($role);
        }

        // always return an array
        return [$role];
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Setter for single role using constants to avoid typos.
     */
    public function setRole(string $role): self
    {
        // normalize role to ROLE_* form
        if (strpos($role, 'ROLE_') !== 0) {
            $role = 'ROLE_' . strtoupper($role);
        }

        $this->role = $role;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary, sensitive data if any
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTimeInterface
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?\DateTimeInterface $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

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
