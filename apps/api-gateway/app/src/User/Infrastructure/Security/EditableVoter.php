<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\Common\Domain\Model\EditableInterface;
use App\User\Domain\Model\UserableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class EditableVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [EditableInterface::CREATE, EditableInterface::READ, EditableInterface::UPDATE, EditableInterface::DELETE])) {
            return false;
        }

        // only vote on editable
        if (!$subject instanceof EditableInterface) {
            return false;
        }

        // only vote on userable
        if (!$subject instanceof UserableInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$token->getUser() instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return $token->getUser()->getId() === $subject->getUser()->getId();

        throw new \LogicException('This code should not be reached!');
    }
}
