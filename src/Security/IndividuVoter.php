<?php

namespace App\Security;

use App\Entity\Individu;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IndividuVoter extends Voter
{
    public const CREATE = 'INDIVIDU_CREATE';
    public const EDIT = 'INDIVIDU_EDIT';
    public const DELETE = 'INDIVIDU_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])
            && ($subject instanceof Individu || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        return $user->hasRole('ROLE_ADMIN');
    }
}
