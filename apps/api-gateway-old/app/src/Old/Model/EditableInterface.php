<?php

declare(strict_types=1);

namespace App\Model;

use App\User\Model\UserInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

interface EditableInterface
{
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    public function getEditable(): bool;

    public function setEditable(?AuthorizationCheckerInterface $authorizationChecker = null, ?UserInterface $currentUser = null): self;
}
