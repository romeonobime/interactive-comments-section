<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
class BaseReply extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public int $commentId;

    #[LiveProp]
    public int $replyId;

    #[LiveProp]
    public int $userId;

    #[LiveProp]
    public string $username;

    #[LiveProp]
    public string $replyingTo;

    #[LiveProp]
    public string $png;

    #[LiveProp]
    public string $webp;

    #[LiveProp(writable: true)]
    public string $content;

    #[LiveProp(writable: true)]
    public string $replyContent = "";

    #[LiveProp]
    public int $score;

    #[LiveProp]
    public bool $isReplying = false;

    #[LiveProp]
    public bool $isDeleting = false;

    #[LiveProp]
    public bool $isEditing = false;

    #[LiveAction]
    public function setIsReplying()
    {
        $this->isReplying = !$this->isReplying;
    }

    #[LiveAction]
    public function setIsDeleting()
    {
        $this->isDeleting = !$this->isDeleting;
    }

    #[LiveAction]
    public function setIsEditing()
    {
        $this->isEditing = !$this->isEditing;
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
    public function editReply()
    {
        $this->setIsEditing();
        $this->emit(
            'replyUpdated',
            [
                'content' => $this->content,
                'id' => $this->replyId
            ],
            'TheCommentList'
        );
    }

    #[LiveAction]
    public function deleteReply()
    {
        $this->setIsDeleting();
        $this->emit(
            'replyDeleted',
            [
                'id' => $this->commentId,
                'replyid' => $this->replyId
            ],
            'TheCommentList'
        );
        $this->emit('getReplies');
    }

    #[LiveAction]
    public function increaseScore()
    {
        $this->score++;
        $this->emit(
            'replyScoreIncreased',
            [
                'id' => $this->replyId,
            ],
            'TheCommentList'
        );
    }

    #[LiveAction]
    public function decreaseScore()
    {
        $this->score--;
        $this->emit(
            'replyscoreDecreased',
            [
                'id' => $this->replyId,
            ],
            'TheCommentList'
        );
    }
}
