<?php

namespace App\Domain\Opinion\Voter;

use App\Entity\Opinion;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class OpinionVoter extends Voter
{
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['POST_EDIT', 'POST_DELETE'])
            && $subject instanceof Opinion;
    }

    /**
     * @param string $attribute
     * @param Opinion $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'POST_EDIT':
                return $subject->getUser()->getId() === $user->getId();
            case 'POST_DELETE':
                return
                    $this->security->isGranted('ROLE_ADMIN') ||
                    $subject->getUser()->getId() === $user->getId();
        }

        return false;
    }
}
