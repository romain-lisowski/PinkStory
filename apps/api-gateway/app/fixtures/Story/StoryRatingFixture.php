<?php

namespace App\Fixture\Story;

use App\Fixture\User\UserFixture;
use App\Story\Entity\StoryRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class StoryRatingFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storyRating = new StoryRating(4, $this->getReference('story-first'), $this->getReference('user-yannis'));
        $manager->persist($storyRating);

        $storyRating = new StoryRating(5, $this->getReference('story-first'), $this->getReference('user-romain'));
        $manager->persist($storyRating);

        $storyRating = new StoryRating(5, $this->getReference('story-first'), $this->getReference('user-leslie'));
        $manager->persist($storyRating);

        $storyRating = new StoryRating(4, $this->getReference('story-second'), $this->getReference('user-yannis'));
        $manager->persist($storyRating);

        $storyRating = new StoryRating(4, $this->getReference('story-second'), $this->getReference('user-romain'));
        $manager->persist($storyRating);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StoryFixture::class,
            UserFixture::class,
        ];
    }
}
