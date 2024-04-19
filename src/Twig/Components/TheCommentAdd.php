<?php

namespace App\Twig\Components;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class TheCommentAdd extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Comment $initialFormData = null;
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, $this->initialFormData);
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

        $this->emit('commentAdded');
    }
}
