<?php

declare(strict_types=1);

namespace App\Language\Query\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Language\Domain\Model\Language as DomainLanguage;
use Symfony\Component\Serializer\Annotation as Serializer;

class LanguageMedium extends Language implements ImageableInterface
{
    use ImageableTrait;

    private string $title;
    private string $locale;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @Serializer\Ignore()
     */
    public static function getImageBasePath(): string
    {
        return DomainLanguage::getImageBasePath();
    }
}
