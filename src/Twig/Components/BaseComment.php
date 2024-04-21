<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\CommentRepository;
use Doctrine\ORM\PersistentCollection;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use App\Entity\Comment;
use App\Entity\Reply;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
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

    #[LiveProp]
    public int $score;

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

    public function getReplies()
    {
        $comment = $this->commentRepository->findOneBy([ "id" => $this->commentId ]);
        return $comment->getReplies();
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

    #[LiveListener('replyAdded')]
    public function close()
    {
        $this->isReplying = false;
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
}
