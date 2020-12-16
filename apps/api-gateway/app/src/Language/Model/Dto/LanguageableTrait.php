<?php

declare(strict_types=1);

namespace App\Language\Model\Dto;

use App\Language\Model\LanguageableTrait as ModelLanguageableTrait;
use App\Language\Model\LanguageInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

trait LanguageableTrait
{
    use ModelLanguageableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private LanguageInterface $language;
}
