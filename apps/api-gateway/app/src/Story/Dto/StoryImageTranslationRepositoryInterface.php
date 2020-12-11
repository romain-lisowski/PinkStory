<?php

declare(strict_types=1);

namespace App\Story\Dto;

use App\Language\Model\Entity\Language;
use Doctrine\Common\Collections\Collection;

interface StoryImageTranslationRepositoryInterface
{
    public function populateStoryImages(Collection $storyImages, Language $language): void;
}
