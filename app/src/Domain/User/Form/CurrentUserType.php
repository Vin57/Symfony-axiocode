<?php

namespace App\Domain\User\Form;

use App\Domain\User\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CurrentUserType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $users = [$this->security->getUser()];
        $resolver->setDefaults([
            'class' => User::class,
            'choices' => $users,
            'empty_data' => $this->security->getUser()->getId(),
            'choice_label' => fn (User $user) => $user->getLogin(),
            'attr' => ['hidden' => true]
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
