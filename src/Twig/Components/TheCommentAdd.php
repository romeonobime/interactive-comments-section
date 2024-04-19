<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent]
class TheCommentAdd extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $content = "";
}
