<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\Reply;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class TheCommentList extends AbstractController
{
    use DefaultActionTrait;

    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getComments(): array
    {
        return $this->commentRepository->findAll();
    }

    #[LiveListener('commentAdded')]
    public function addComment(#[LiveArg] string $content, EntityManagerInterface $entityManager)
    {
        $comment = new Comment;
        $comment->setContent($content);
        $comment->setUser($this->getUser());
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('commentUpdated')]
    public function updateComment(#[LiveArg] string $content, #[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->setContent($content);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('commentDeleted')]
    public function deleteComment(#[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $entityManager->remove($comment);
        $entityManager->flush();
    }

    #[LiveListener('replyAdded')]
    public function addReply(#[LiveArg] int $id, #[LiveArg] string $content, #[LiveArg] string $replyingto, EntityManagerInterface $entityManager)
    {
        $reply = new Reply;
        $reply->setContent($content);
        $reply->setUser($this->getUser());
        $reply->setReplyingTo($replyingto);
        $entityManager->persist($reply);
        $entityManager->flush();

        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->addReply($reply);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('replyUpdated')]
    public function updateReply(#[LiveArg] int $id, #[LiveArg] string $content, #[LiveArg] string $replyingto, EntityManagerInterface $entityManager)
    {
        $reply = new Reply;
        $reply->setContent($content);
        $reply->setUser($this->getUser());
        $reply->setReplyingTo($replyingto);
        $entityManager->persist($reply);
        $entityManager->flush();

        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->addReply($reply);
        $entityManager->persist($comment);
        $entityManager->flush();
    }
}
