<?php

declare(strict_types=1);

namespace App\Common\Domain\Translation;

interface TranslatorInterface
{
    /**
     * @throws \InvalidArgumentException If the locale contains invalid characters
     */
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string;
}
