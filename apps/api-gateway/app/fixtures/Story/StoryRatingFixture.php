<?php

namespace App\Fixture\Story;

use App\Fixture\User\UserFixture;
use App\Story\Domain\Model\StoryRating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StoryRatingFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'story-rating-story-first-yannis' => [
            'rate' => 4,
            'story_reference' => 'story-first',
            'user_reference' => 'user-yannis',
        ],
        'story-rating-story-first-romain' => [
            'rate' => 5,
            'story_reference' => 'story-first',
            'user_reference' => 'user-romain',
        ],
        'story-rating-story-first-leslie' => [
            'rate' => 5,
            'story_reference' => 'story-first',
            'user_reference' => 'user-leslie',
        ],
        'story-rating-story-first-juliette' => [
            'rate' => 4,
            'story_reference' => 'story-first',
            'user_reference' => 'user-juliette',
        ],
        'story-rating-story-first-john' => [
            'rate' => 5,
            'story_reference' => 'story-first',
            'user_reference' => 'user-john',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $storyRatingReference => $data) {
            $storyRating = (new StoryRating())
                ->setRate($data['rate'])
                ->setStory($this->getReference($data['story_reference']))
                ->setUser($this->getReference($data['user_reference']))
            ;

            $manager->persist($storyRating);
            $this->addReference($storyRatingReference, $storyRating);
        }

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
