<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[AsLiveComponent]
class TheLoginForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?User $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(LoginType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function login(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->submitForm();
        /** @var User $user */
        $user = $this->getForm()->getData();
        $unhashedPassword = $user->getPassword();
        $username = $user->getUsername();

        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        if (!$passwordHasher->isPasswordValid($user, $unhashedPassword)) {
            throw new AccessDeniedHttpException();
        }

        $security->login($user);

        return $this->redirectToRoute('app_comment');
    }
}
