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

    #[LiveProp]
    public int $commentId = 0;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getReplies()
    {
        $comment  = $this->commentRepository->find([ "id" => $this->commentId]);
        return $comment->getReplies();
    }

    #[LiveListener('replyAdded')]
    public function addReply(#[LiveArg] int $id, #[LiveArg] string $content, #[LiveArg] string $replyingto, EntityManagerInterface $entityManager)
    {
        $reply = new Reply;
        $reply->setContent($content);
        $reply->setUser($this->getUser());
        $reply->setReplyingTo($replyingto);
        $entityManager->persist($reply);

        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->addReply($reply);
        $entityManager->persist($comment);
        $entityManager->flush();
    }
}
