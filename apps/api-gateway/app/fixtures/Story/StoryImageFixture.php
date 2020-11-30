<?php

namespace App\Fixture\Story;

use App\Fixture\Language\LanguageFixture;
use App\Story\Entity\StoryImage;
use App\Story\Entity\StoryImageTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class StoryImageFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storyImage = new StoryImage('First image');
        new StoryImageTranslation('First image', $storyImage, $this->getReference('language-english'));
        new StoryImageTranslation('Première image', $storyImage, $this->getReference('language-french'));
        $storyImage->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-bdsm-domination'))
            ->addStoryTheme($this->getReference('story-theme-extreme'))
        ;
        $manager->persist($storyImage);
        $this->addReference('story-image-first', $storyImage);

        $storyImage = new StoryImage('Second image');
        new StoryImageTranslation('Second image', $storyImage, $this->getReference('language-english'));
        new StoryImageTranslation('Deuxième image', $storyImage, $this->getReference('language-french'));
        $storyImage->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-oral-sex'))
            ->addStoryTheme($this->getReference('story-theme-soft'))
        ;
        $manager->persist($storyImage);
        $this->addReference('story-image-second', $storyImage);

        $storyImage = new StoryImage('Third image');
        new StoryImageTranslation('Third image', $storyImage, $this->getReference('language-english'));
        new StoryImageTranslation('Troisième image', $storyImage, $this->getReference('language-french'));
        $storyImage->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-sodomy'))
            ->addStoryTheme($this->getReference('story-theme-hard'))
        ;
        $manager->persist($storyImage);
        $this->addReference('story-image-third', $storyImage);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LanguageFixture::class,
            StoryThemeFixture::class,
        ];
    }
}
