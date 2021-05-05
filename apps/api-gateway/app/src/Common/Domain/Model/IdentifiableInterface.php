<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

interface IdentifiableInterface
{
    public function getId(): string;

    public static function extractIds(array $identifiables): array;
}
