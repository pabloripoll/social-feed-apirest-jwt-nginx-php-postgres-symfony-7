<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Feed\Entity\FeedPost;
use App\Domain\Member\Entity\MemberModerationType;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members_moderations")]
class MemberModeration
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
    #[ORM\JoinColumn(name: "admin_user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private User $admin_user_id;

    public function getAdminUserId(): User
    {
        return $this->admin_user_id;
    }

    public function setAdminUserId(User $admin_user_id): self
    {
        $this->admin_user_id = $admin_user_id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: MemberModerationType::class)]
    #[ORM\JoinColumn(name: "type_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private MemberModerationType $type_id;

    public function getTypeId(): MemberModerationType
    {
        return $this->type_id;
    }

    public function setTypeId(MemberModerationType $type_id): self
    {
        $this->type_id = $type_id;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_applied = false;

    public function getIsApplied(): bool
    {
        return $this->is_applied;
    }

    public function setIsApplied(bool $is_applied): self
    {
        $this->is_applied = $is_applied;
        return $this;
    }

    #[ORM\Column(type: "datetime", nullable: true)]
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

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_on_member = false;

    public function getIsOnMember(): bool
    {
        return $this->is_on_member;
    }

    public function setIsOnMember(bool $is_on_member): self
    {
        $this->is_on_member = $is_on_member;
        return $this;
    }

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private bool $is_on_post = false;

    public function getIsOnPost(): bool
    {
        return $this->is_on_post;
    }

    public function setIsOnPost(bool $is_on_post): self
    {
        $this->is_on_post = $is_on_post;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "member_user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private User $member_user_id;

    public function getMemberUserId(): User
    {
        return $this->member_user_id;
    }

    public function setMemberUserId(User $member_user_id): self
    {
        $this->member_user_id = $member_user_id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: FeedPost::class)]
    #[ORM\JoinColumn(name: "member_post_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private FeedPost $member_post_id;

    public function getMemberPostId(): FeedPost
    {
        return $this->member_post_id;
    }

    public function setMemberPostId(FeedPost $member_post_id): self
    {
        $this->member_post_id = $member_post_id;
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
