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

    /** @var PersistentCollection $replies */
    public $replies;


    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getReplies(): PersistentCollection
    {
        return $this->replies;
    }

    #[LiveAction]
    public function setIsEditing()
    {
        $this->isEditing = !$this->isEditing;
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
