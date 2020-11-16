<?php

namespace App\Fixture\Story;

use App\Story\Entity\StoryImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class StoryImageFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storyImage = new StoryImage('Première image');
        $storyImage->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-bdsm-domination'))
            ->addStoryTheme($this->getReference('story-theme-extreme'))
        ;
        $manager->persist($storyImage);
        $this->addReference('story-image-first', $storyImage);

        $storyImage = new StoryImage('Deuxième image');
        $storyImage->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-oral-sex'))
            ->addStoryTheme($this->getReference('story-theme-soft'))
        ;
        $manager->persist($storyImage);
        $this->addReference('story-image-second', $storyImage);

        $storyImage = new StoryImage('Troisième image');
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
            StoryThemeFixture::class,
        ];
    }
}
