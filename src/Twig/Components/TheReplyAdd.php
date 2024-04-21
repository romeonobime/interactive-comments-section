<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
class TheReplyAdd extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $content = "";

    #[LiveProp]
    public int $commentId = 0;

    #[LiveProp]
    public string $replyingTo = "";


    #[LiveProp]
    public bool $isOpen = false;

}
