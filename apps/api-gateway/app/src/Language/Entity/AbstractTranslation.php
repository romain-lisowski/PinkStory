<?php

declare(strict_types=1);

namespace App\Language\Entity;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractTranslation extends AbstractEntity implements LanguageableInterface
{
    private Language $language;

    public function __construct(Language $language)
    {
        parent::__construct();

        // init values
        $this->setLanguage($language);
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    abstract public function setLanguage(Language $language): self;
}
