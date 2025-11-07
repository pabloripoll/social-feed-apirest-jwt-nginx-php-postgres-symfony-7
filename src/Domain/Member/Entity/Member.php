<?php

namespace App\Domain\Member\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\Entity\User;
use App\Domain\Geo\Entity\GeoRegion;
use App\Domain\Member\Repository\MemberRepository;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "members")]
#[ORM\UniqueConstraint(name: "uniq_members_uid", columns: ["uid"])]
class Member
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
    private ?GeoRegion $region = null;

    public function getRegion(): ?GeoRegion
    {
        return $this->region;
    }

    public function setRegion(?GeoRegion $region): self
    {
        $this->region = $region;
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

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $following_count = 0;

    public function getFollowingCount(): int
    {
        return $this->following_count;
    }

    public function setFollowingCount(int $following_count): self
    {
        $this->following_count = $following_count;
        return $this;
    }

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $followers_count = 0;

    public function getFollowersCount(): int
    {
        return $this->followers_count;
    }

    public function setFollowersCount(int $followers_count): self
    {
        $this->followers_count = $followers_count;
        return $this;
    }

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $posts_count = 0;

    public function getPostsCount(): int
    {
        return $this->posts_count;
    }

    public function setPostsCount(int $posts_count): self
    {
        $this->posts_count = $posts_count;
        return $this;
    }

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $posts_votes_up_count = 0;

    public function getPostsVotesUpCount(): int
    {
        return $this->posts_votes_up_count;
    }

    public function setPostsVotesUpCount(int $posts_votes_up_count): self
    {
        $this->posts_votes_up_count = $posts_votes_up_count;
        return $this;
    }

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $posts_votes_down_count = 0;

    public function getPostsVotesDownCount(): int
    {
        return $this->posts_votes_down_count;
    }

    public function setPostsVotesDownCount(int $posts_votes_down_count): self
    {
        $this->posts_votes_down_count = $posts_votes_down_count;
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
