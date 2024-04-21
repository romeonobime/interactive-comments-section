<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\Reply;
use App\Repository\CommentRepository;
use App\Repository\ReplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class TheReplyList extends AbstractController
{
    use DefaultActionTrait;

    private CommentRepository $commentRepository;
    private ReplyRepository $replyRepository;

    #[LiveProp]
    public int $commentId = 0;

    public function __construct(CommentRepository $commentRepository, ReplyRepository $replyRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->replyRepository = $replyRepository;
    }

    #[LiveListener('getReplies')]
    public function getReplies()
    {
        $comment  = $this->commentRepository->find([ "id" => $this->commentId]);
        return $comment->getReplies();
    }
}
