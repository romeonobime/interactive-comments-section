<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\CommentRepository;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
class BaseReply extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    private CommentRepository $commentRepository;

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

    #[LiveProp]
    public int $score;

    #[LiveProp]
    public bool $isReplying = false;

    #[LiveProp]
    public bool $isDeleting = false;

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
    public function setIsDeleting()
    {
        $this->isDeleting = !$this->isDeleting;
    }
}
