<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthentificationController extends AbstractController
{
    #[Route('/authentification/signup', name: 'app_authentification_signup')]
    public function signup(): Response
    {
        return $this->render('pages/authentification/signup.html.twig');
    }

    #[Route('/authentification/login', name: 'app_authentification_login')]
    public function login(): Response
    {
        return $this->render('pages/authentification/login.html.twig');
    }
}
