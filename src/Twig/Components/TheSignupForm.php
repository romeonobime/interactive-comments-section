<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\SignupType;
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

#[AsLiveComponent]
class TheSignupForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?User $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(SignupType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function signup(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->submitForm();
        /** @var User $user */
        $user = $this->getForm()->getData();
        $unhashedPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $unhashedPassword
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $security->login($user);

        return $this->redirectToRoute('app_comment');
    }
}
