<?php

namespace App\Fixture\Story;

use App\Fixture\User\UserFixture;
use App\Story\Entity\Story;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class StoryFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storyParent = new Story('Première histoire', 'Contenu de la première histoire', $this->getReference('user-romain'), null, $this->getReference('story-image-first'));
        $storyParent->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-office'))
            ->addStoryTheme($this->getReference('story-theme-threesome'))
            ->addStoryTheme($this->getReference('story-theme-bdsm-domination'))
            ->addStoryTheme($this->getReference('story-theme-extreme'))
        ;
        $manager->persist($storyParent);

        $storyParent = new Story('Deuxième histoire', 'Contenu de la deuxième histoire', $this->getReference('user-leslie'), null, $this->getReference('story-image-second'));
        $storyParent->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-office'))
        ;
        $manager->persist($storyParent);

        $storyChild = new Story('Premier chapitre de la deuxième histoire', 'Contenu du premier chapitre de la deuxième histoire', $this->getReference('user-leslie'), $storyParent, $this->getReference('story-image-second'));
        $storyChild->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-office'))
            ->addStoryTheme($this->getReference('story-theme-oral-sex'))
            ->addStoryTheme($this->getReference('story-theme-soft'))
        ;
        $manager->persist($storyChild);

        $storyChild = new Story('Deuxième chapitre de la deuxième histoire', 'Contenu du deuxième chapitre de la deuxième histoire', $this->getReference('user-leslie'), $storyParent, $this->getReference('story-image-third'));
        $storyChild->addStoryTheme($this->getReference('story-theme-heterosexual'))
            ->addStoryTheme($this->getReference('story-theme-home'))
            ->addStoryTheme($this->getReference('story-theme-sodomy'))
            ->addStoryTheme($this->getReference('story-theme-hard'))
        ;
        $manager->persist($storyChild);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            StoryImageFixture::class,
            StoryThemeFixture::class,
        ];
    }
}