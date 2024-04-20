<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent]
class TheCommentReply extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $content = "";

    #[LiveProp]
    public int $commentId = 0;

    #[LiveProp]
    public string $replyingTo = "";
}
