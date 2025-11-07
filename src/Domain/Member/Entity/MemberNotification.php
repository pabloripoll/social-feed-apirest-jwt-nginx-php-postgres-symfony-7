<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Member\Entity\MemberNotificationType;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members_notifications")]
#[ORM\Index(columns: ['created_at'])]
class MemberNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: MemberNotificationType::class)]
    #[ORM\JoinColumn(name: "type_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private MemberNotificationType $type_id;

    public function getTypeId(): MemberNotificationType
    {
        return $this->type_id;
    }

    public function setTypeId(MemberNotificationType $type_id): self
    {
        $this->type_id = $type_id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private User $user_id;

    public function getUserId(): User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_opened = false;

    public function getIsOpened(): bool
    {
        return $this->is_opened;
    }

    public function setIsOpened(bool $is_opened): self
    {
        $this->is_opened = $is_opened;
        return $this;
    }

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $openend_at;

    public function getOpenedAt(): \DateTimeInterface
    {
        return $this->openend_at;
    }

    public function setOpenedAt(\DateTimeInterface $openend_at): self
    {
        $this->openend_at = $openend_at;
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

    #[ORM\Column(type: "string", length: 512)]
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "last_member_user_id", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    private User $last_member_user_id;

    public function getLastMemberUserId(): User
    {
        return $this->last_member_user_id;
    }

    public function setLastMemberUserId(User $last_member_user_id): self
    {
        $this->last_member_user_id = $last_member_user_id;
        return $this;
    }

    #[ORM\Column(type: "string", nullable: true, length: 32)]
    private string $last_member_nickname;

    public function getLastMemberNickname(): string
    {
        return $this->last_member_nickname;
    }

    public function setLastMemberNickname(string $last_member_nickname): self
    {
        $this->last_member_nickname = $last_member_nickname;
        return $this;
    }

    #[ORM\Column(type: "string", nullable: true, length: 32)]
    private string $last_member_avatar;

    public function getLastMemberAvatar(): string
    {
        return $this->last_member_avatar;
    }

    public function setLastMemberAvatar(string $last_member_avatar): self
    {
        $this->last_member_avatar = $last_member_avatar;
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
