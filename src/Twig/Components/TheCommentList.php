<?php

namespace App\Twig\Components;

use App\Repository\CommentRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class TheCommentList
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getComments(): array
    {
        return $this->commentRepository->findAll();
    }
}
