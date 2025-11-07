<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members_notification_types")]
#[ORM\UniqueConstraint(name: "uniq_members_notification_type_key", columns: ["key"])]
class MemberNotificationType
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
    private string $key;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    #[ORM\Column(type: "string", length: 64)]
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    #[ORM\Column(type: "string", length: 512)]
    private string $message_singular;

    public function getMessageSingular(): string
    {
        return $this->message_singular;
    }

    public function setMessageSingular(string $message_singular): self
    {
        $this->message_singular = $message_singular;
        return $this;
    }

    #[ORM\Column(type: "string", length: 512)]
    private string $message_multiple;

    public function getMessageMultiple(): string
    {
        return $this->message_multiple;
    }

    public function setMessageMultiple(string $message_multiple): self
    {
        $this->message_multiple = $message_multiple;
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
