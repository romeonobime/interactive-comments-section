<?php

namespace App\Twig\Components;

use App\Repository\CommentRepository;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

#[AsLiveComponent]
class TheComment extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Comment $initialFormData = null;
    private CommentRepository $commentRepository;

    /** @var Security */
    private $security;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, $this->initialFormData);
    }

    public function getComments(): array
    {
        return $this->commentRepository->findAll();
    }

    #[LiveAction]
    public function addComment(EntityManagerInterface $entityManager)
    {
        $this->submitForm();

        /** @var Comment $comment */
        $comment = $this->getForm()->getData();
        $comment->setUser($this->getUser());
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    #[LiveAction]
    public function deleteComment(#[LiveArg] int $id, EntityManagerInterface $entityManager)
    {
        $comment = $this->commentRepository->findOneBy([ "id" => $id ]);
        $entityManager->remove($comment);
        $entityManager->flush();
    }
}
