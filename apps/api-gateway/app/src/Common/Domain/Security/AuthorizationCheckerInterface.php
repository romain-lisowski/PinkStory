<?php

declare(strict_types=1);

namespace App\Common\Domain\Security;

interface AuthorizationCheckerInterface
{
    /**
     * @param mixed $subject
     *
     * @throws AccessDeniedException
     */
    public function isGranted(string $attribute, $subject): void;
}
