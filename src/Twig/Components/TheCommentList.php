<?php

namespace App\Twig\Components;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
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

    #[LiveListener('commentAdded')]
    public function getComments(): array
    {
        return $this->commentRepository->findAll();
    }

    #[LiveAction]
    public function deleteComment(#[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $entityManager->remove($comment);
        $entityManager->flush();
    }
}
