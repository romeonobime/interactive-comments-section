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

#[AsLiveComponent]
class TheCommentList extends AbstractController
{
    use DefaultActionTrait;

    private CommentRepository $commentRepository;
    private ReplyRepository $replyRepository;

    public function __construct(CommentRepository $commentRepository, ReplyRepository $replyRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->replyRepository = $replyRepository;
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

        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->addReply($reply);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('replyUpdated')]
    public function updateReply(#[LiveArg] string $content, #[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $id ]);
        $reply->setContent($content);
        $entityManager->persist($reply);
        $entityManager->flush();
    }

    #[LiveListener('replyDeleted')]
    public function deleteReply(#[LiveArg] int $replyid, #[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $replyid ]);

        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $comment->removeReply($reply);
        $entityManager->remove($reply);
        $entityManager->flush();
    }

    #[LiveListener('commentScoreIncreased')]
    public function increaseScore(#[LiveArg] int $id, #[LiveArg] int $score, #[LiveArg] bool $hasdisliked,EntityManagerInterface $entityManager)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);

        if ($hasdisliked) {
            $comment->removeUsersDisLiked($this->getUser());
        }

        if ( ! $hasdisliked) {
            $comment->addUsersLiked($this->getUser());
        }

        $comment->setScore($score);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('commentScoreDecreased')]
    public function decreasedScore(#[LiveArg] int $id,#[LiveArg] int $score, #[LiveArg] bool $hasliked, EntityManagerInterface $entityManager)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);

        if ($hasliked) {
            $comment->removeUsersLiked($this->getUser());
        }

        if ( ! $hasliked) {
            $comment->addUsersDisLiked($this->getUser());
        }

        $comment->setScore($score);
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveListener('replyScoreIncreased')]
    public function increaseScoreReply(#[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $id ]);
        $replyScore = $reply->getScore();
        $reply->setScore(++$replyScore);
        $entityManager->persist($reply);
        $entityManager->flush();
    }

    #[LiveListener('replyscoreDecreased')]
    public function decreaseScoreReply(#[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $id ]);
        $replyScore = $reply->getScore();
        $reply->setScore(--$replyScore);
        $entityManager->persist($reply);
        $entityManager->flush();
    }
}
