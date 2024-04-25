<?php

namespace App\Twig\Components;

use App\Entity\Reply;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    private ReplyRepository $replyRepository;

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

    public function __construct(ReplyRepository $replyRepository)
    {
        $this->replyRepository = $replyRepository;
    }


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
    public function increaseScore(EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $this->replyId ]);
        $replyUsersLiked = $reply->getUsersLiked();
        $replyUsersDisLiked = $reply->getUsersDisLiked();
        $currentUser = $this->getUser();

        $hasDisLiked = $replyUsersDisLiked->contains($currentUser);
        $hasLiked = $replyUsersLiked->contains($currentUser);

        if($hasLiked) {
            return;
        }

        if ($hasDisLiked) {
            $reply->removeUsersDisLiked($currentUser);
        }

        if ( ! $hasDisLiked) {
            $reply->addUsersLiked($currentUser);
        }

        $this->score++;
        $reply->setScore($this->score);
        $entityManager->persist($reply);
        $entityManager->flush();
    }

        #[LiveAction]
    public function decreaseScore(EntityManagerInterface $entityManager)
    {
        /** @var Reply $reply */
        $reply = $this->replyRepository->findOneBy([ "id" => $this->replyId ]);
        $replyUsersLiked = $reply->getUsersLiked();
        $replyUsersDisLiked = $reply->getUsersDisLiked();
        $currentUser = $this->getUser();

        $hasDisLiked = $replyUsersDisLiked->contains($currentUser);
        $hasLiked = $replyUsersLiked->contains($currentUser);

        if($hasDisLiked) {
            return;
        }

        if ($hasLiked) {
            $reply->removeUsersLiked($currentUser);
        }

        if ( ! $hasLiked) {
            $reply->addUsersDisLiked($currentUser);
        }

        $this->score--;
        $reply->setScore($this->score);
        $entityManager->persist($reply);
        $entityManager->flush();
    }
}
