<?php

namespace App\Entity;

use App\Repository\ReplyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;

#[ORM\Entity(repositoryClass: ReplyRepository::class)]
class Reply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column(length: 255)]
    private ?string $replyingTo = null;

    #[ORM\ManyToOne(inversedBy: 'replies')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'replies')]
    private ?Comment $comment = null;

    /**
     * @var Collection<int, User>
     */
    #[JoinTable(name: 'reply_users_liked')]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'liked_replies')]
    private Collection $users_liked;

    /**
     * @var Collection<int, User>
     */
    #[JoinTable(name: 'reply_users_disliked')]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'disliked_replies')]
    private Collection $users_disliked;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->score = 0;
        $this->users_liked = new ArrayCollection();
        $this->users_disliked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getReplyingTo(): ?string
    {
        return $this->replyingTo;
    }

    public function setReplyingTo(string $replyingTo): static
    {
        $this->replyingTo = $replyingTo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersLiked(): Collection
    {
        return $this->users_liked;
    }

    public function addUsersLiked(User $usersLiked): static
    {
        if (!$this->users_liked->contains($usersLiked)) {
            $this->users_liked->add($usersLiked);
        }

        return $this;
    }

    public function removeUsersLiked(User $usersLiked): static
    {
        $this->users_liked->removeElement($usersLiked);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersDisliked(): Collection
    {
        return $this->users_disliked;
    }

    public function addUsersDisliked(User $usersDisliked): static
    {
        if (!$this->users_disliked->contains($usersDisliked)) {
            $this->users_disliked->add($usersDisliked);
        }

        return $this;
    }

    public function removeUsersDisliked(User $usersDisliked): static
    {
        $this->users_disliked->removeElement($usersDisliked);

        return $this;
    }
}
