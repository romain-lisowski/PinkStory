<?php

declare(strict_types=1);

namespace App\User\Voter;

use App\User\Entity\UserableInterface;
use App\User\Entity\UserRole;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserableVoter extends Voter
{
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CREATE, self::READ, self::UPDATE, self::DELETE])) {
            return false;
        }

        // only vote on userable
        if (!$subject instanceof UserableInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // moderators are like small gods
        if (true === $this->authorizationChecker->isGranted(UserRole::ROLE_MODERATOR)) {
            return true;
        }

        // you know subject is a userable, thanks to `supports()`
        $userable = $subject;

        if ($userable->getUser() === $user) {
            return true;
        }

        return false;

        throw new LogicException('This code should not be reached!');
    }
}
