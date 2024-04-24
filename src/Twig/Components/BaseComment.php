<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\CommentRepository;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use App\Entity\Comment;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class BaseComment extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    private CommentRepository $commentRepository;

    #[LiveProp]
    public int $commentId;

    #[LiveProp]
    public int $userId;

    #[LiveProp]
    public string $username;

    #[LiveProp]
    public string $png;

    #[LiveProp]
    public string $webp;

    #[LiveProp(writable: true)]
    public string $content;

    #[LiveProp(writable: true)]
    public string $replyContent = "";

    #[LiveProp]
    public int $score= 0;

    #[LiveProp]
    public bool $isEditing = false;

    #[LiveProp]
    public bool $isDeleting = false;

    #[LiveProp]
    public bool $isReplying = false;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    #[LiveListener('getReplies')]
    public function getReplies()
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        return $comment->getReplies()->toArray();
    }

    #[LiveAction]
    public function setIsEditing()
    {
        $this->isEditing = !$this->isEditing;
    }

    #[LiveAction]
    public function setIsDeleting()
    {
        $this->isDeleting = !$this->isDeleting;
    }

    #[LiveAction]
    public function setIsReplying()
    {
        $this->isReplying = !$this->isReplying;
    }

    #[LiveAction]
    public function addReply()
    {
        $this->setIsReplying();
        $this->emit(
            'replyAdded',
            [
                'content' => $this->replyContent,
                'id' => $this->commentId,
                'replyingto' => $this->username,
            ],
            'TheCommentList'
        );
        $this->emit('getReplies');
    }

    #[LiveAction]
    public function editComment()
    {
        $this->setIsEditing();
        $this->emit(
            'commentUpdated',
            [
                'content' => $this->content,
                'id' => $this->commentId
            ],
            'TheCommentList'
        );
    }

    #[LiveAction]
    public function deleteReply()
    {
        $this->setIsDeleting();
        $this->emit(
            'commentDeleted',
            [
                'id' => $this->commentId,
            ],
            'TheCommentList'
        );
    }

    #[LiveAction]
    public function increaseScore(EntityManagerInterface $entityManager)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        $commentUsersLiked = $comment->getUsersLiked();
        $commentUsersDisLiked = $comment->getUsersDisLiked();
        $currentUser = $this->getUser();

        $hasDisLiked = $commentUsersDisLiked->contains($currentUser);
        $hasLiked = $commentUsersLiked->contains($currentUser);

        if($hasLiked) {
            return;
        }

        if ($hasDisLiked) {
            $comment->removeUsersDisLiked($currentUser);
        }

        if ( ! $hasDisLiked) {
            $comment->addUsersLiked($currentUser);
        }

        $this->score++;
        $comment->setScore($this->score);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveAction]
    public function decreaseScore(EntityManagerInterface $entityManager)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        $commentUsersLiked = $comment->getUsersLiked();
        $commentUsersDisLiked = $comment->getUsersDisLiked();
        $currentUser = $this->getUser();

        $hasDisLiked = $commentUsersDisLiked->contains($currentUser);
        $hasLiked = $commentUsersLiked->contains($currentUser);

        if($hasDisLiked) {
            return;
        }

        if ($hasLiked) {
            $comment->removeUsersLiked($currentUser);
        }

        if ( ! $hasLiked) {
            $comment->addUsersDisLiked($currentUser);
        }

        $this->score--;
        $comment->setScore($this->score);
        $entityManager->persist($comment);
        $entityManager->flush();
    }
}
