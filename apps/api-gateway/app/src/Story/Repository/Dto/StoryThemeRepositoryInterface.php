<?php

declare(strict_types=1);

namespace App\Story\Repository\Dto;

use App\Language\Model\LanguageInterface;
use Doctrine\Common\Collections\Collection;

interface StoryThemeRepositoryInterface
{
    public function populateStoryImages(Collection $storyImages, LanguageInterface $language): void;
}
