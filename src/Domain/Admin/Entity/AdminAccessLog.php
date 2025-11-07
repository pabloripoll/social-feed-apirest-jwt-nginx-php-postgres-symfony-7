<?php

namespace App\Domain\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Admin\Repository\AdminAccessLogRepository;

#[ORM\Entity(repositoryClass: AdminAccessLogRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'admins_access_logs')]
#[ORM\Index(columns: ['expires_at'])]
#[ORM\Index(columns: ['created_at'])]
#[ORM\Index(columns: ['token'])]
class AdminAccessLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
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

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $is_terminated = false;

    public function getIsTerminated(): bool
    {
        return $this->is_terminated;
    }

    public function setIsTerminated(bool $is_terminated): self
    {
        $this->is_terminated = $is_terminated;
        return $this;
    }

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $is_expired = false;

    public function getIsExpired(): bool
    {
        return $this->is_expired;
    }

    public function setIsExpired(bool $is_expired): self
    {
        $this->is_expired = $is_expired;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTimeInterface $expires_at;

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expires_at;
    }

    public function setExpiresAt(\DateTimeInterface $expires_at): self
    {
        $this->expires_at = $expires_at;
        return $this;
    }

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $refresh_count = 0;

    public function getRefreshCount(): int
    {
        return $this->refresh_count;
    }

    public function setRefreshCount(int $refresh_count): self
    {
        $this->refresh_count = $refresh_count;
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

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $ip_address = null;

    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $user_agent = null;

    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    public function setUserAgent(string $user_agent): self
    {
        $this->user_agent = $user_agent;
        return $this;
    }

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $requests_count = 0;

    public function getRequestsCount(): int
    {
        return $this->requests_count;
    }

    public function setRequestsCount(int $requests_count): self
    {
        $this->requests_count = $requests_count;
        return $this;
    }

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $payload = null;

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    #[ORM\Column(type: 'text')]
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
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
