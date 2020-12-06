<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

interface AuthorizationManagerInterface
{
    /**
     * Checks if is granted.
     *
     * @param mixed $subject
     *
     * @throws AccessDeniedException
     */
    public function isGranted(string $attribute, $subject): void;
}
