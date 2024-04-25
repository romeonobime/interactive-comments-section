<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Reply>
     */
    #[ORM\OneToMany(targetEntity: Reply::class, mappedBy: 'user')]
    private Collection $replies;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comments;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Images $image = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'users_liked')]
    private Collection $likedComments;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'users_disliked')]
    private Collection $dislikedComments;

    /**
     * @var Collection<int, Reply>
     */
    #[ORM\ManyToMany(targetEntity: Reply::class, mappedBy: 'users_liked')]
    private Collection $liked_replies;

    /**
     * @var Collection<int, Reply>
     */
    #[ORM\ManyToMany(targetEntity: Reply::class, mappedBy: 'users_disliked')]
    private Collection $disliked_replies;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likedComments = new ArrayCollection();
        $this->dislikedComments = new ArrayCollection();
        $this->liked_replies = new ArrayCollection();
        $this->disliked_replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Reply>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): static
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setUser($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): static
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getUser() === $this) {
                $reply->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(?Images $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getLikedComments(): Collection
    {
        return $this->likedComments;
    }

    public function addLikedComment(Comment $likedComment): static
    {
        if (!$this->likedComments->contains($likedComment)) {
            $this->likedComments->add($likedComment);
            $likedComment->addUsersLiked($this);
        }

        return $this;
    }

    public function removeLikedComment(Comment $likedComment): static
    {
        if ($this->likedComments->removeElement($likedComment)) {
            $likedComment->removeUsersLiked($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getDislikedComments(): Collection
    {
        return $this->dislikedComments;
    }

    public function addDislikedComment(Comment $dislikedComment): static
    {
        if (!$this->dislikedComments->contains($dislikedComment)) {
            $this->dislikedComments->add($dislikedComment);
            $dislikedComment->addUsersDisliked($this);
        }

        return $this;
    }

    public function removeDislikedComment(Comment $dislikedComment): static
    {
        if ($this->dislikedComments->removeElement($dislikedComment)) {
            $dislikedComment->removeUsersDisliked($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reply>
     */
    public function getLikedReplies(): Collection
    {
        return $this->liked_replies;
    }

    public function addLikedReply(Reply $likedReply): static
    {
        if (!$this->liked_replies->contains($likedReply)) {
            $this->liked_replies->add($likedReply);
            $likedReply->addUsersLiked($this);
        }

        return $this;
    }

    public function removeLikedReply(Reply $likedReply): static
    {
        if ($this->liked_replies->removeElement($likedReply)) {
            $likedReply->removeUsersLiked($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reply>
     */
    public function getDislikedReplies(): Collection
    {
        return $this->disliked_replies;
    }

    public function addDislikedReply(Reply $dislikedReply): static
    {
        if (!$this->disliked_replies->contains($dislikedReply)) {
            $this->disliked_replies->add($dislikedReply);
            $dislikedReply->addUsersDisliked($this);
        }

        return $this;
    }

    public function removeDislikedReply(Reply $dislikedReply): static
    {
        if ($this->disliked_replies->removeElement($dislikedReply)) {
            $dislikedReply->removeUsersDisliked($this);
        }

        return $this;
    }
}
