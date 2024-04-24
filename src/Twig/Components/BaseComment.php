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
    public function increaseScore()
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        $commentUsersLiked = $comment->getUsersLiked();
        $commentUsersDisLiked = $comment->getUsersDisLiked();

        $hasDisLiked = $commentUsersDisLiked->contains($this->getUser());
        $hasLiked = $commentUsersLiked->contains($this->getUser());

        if($hasLiked) {
            return;
        }

        $this->score++;
        $this->emit(
            'commentScoreIncreased',
            [
                'id' => $this->commentId,
                'score' => $this->score,
                'hasdisliked' => $hasDisLiked,
            ],
            'TheCommentList'
        );
    }

    #[LiveAction]
    public function decreaseScore()
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        $commentUsersLiked = $comment->getUsersLiked();
        $commentUsersDisLiked = $comment->getUsersDisLiked();

        $hasDisLiked = $commentUsersDisLiked->contains($this->getUser());
        $hasLiked = $commentUsersLiked->contains($this->getUser());

        if($hasDisLiked) {
            return;
        }

        $this->score--;
        $this->emit(
            'commentScoreDecreased',
            [
                'id' => $this->commentId,
                'score' => $this->score,
                'hasliked' => $hasLiked,
            ],
            'TheCommentList'
        );
    }
}
