<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

final class LanguageMedium extends Language
{
    public function __construct(string $id = '')
    {
        parent::__construct($id);
    }
}
