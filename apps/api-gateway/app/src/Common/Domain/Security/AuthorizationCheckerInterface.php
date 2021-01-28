<?php

declare(strict_types=1);

namespace App\Common\Domain\Security;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

interface AuthorizationCheckerInterface
{
    /**
     * @param mixed $subject
     *
     * @throws AccessDeniedException
     */
    public function isGranted(string $attribute, $subject): void;
}
