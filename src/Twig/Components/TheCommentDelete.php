<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;

#[AsLiveComponent]
class TheCommentDelete extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public bool $isOpen = false;

    #[LiveProp]
    public int $id = 0;

    #[LiveListener('openModal')]
    public function openModal(#[LiveArg] int $id)
    {
        $this->isOpen = true;
        $this->id = $id;
    }

    #[LiveListener('commentDeleted')]
    public function closeModal()
    {
        $this->isOpen = false;
    }

    #[LiveListener('closeModal')]
    public function close()
    {
       $this->closeModal();
    }
}
