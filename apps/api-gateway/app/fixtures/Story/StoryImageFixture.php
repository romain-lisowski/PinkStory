<?php

namespace App\Fixture\Story;

use App\Fixture\Language\LanguageFixture;
use App\Story\Domain\Model\StoryImage;
use App\Story\Domain\Model\StoryImageHasStoryTheme;
use App\Story\Domain\Model\StoryImageTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class StoryImageFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'story-image-first' => [
            'id' => 'd68e4d8c-371a-4a43-bf6a-668bf5cf2a39',
            'reference' => 'First image',
            'translations' => [
                'language-english' => [
                    'title' => 'First image',
                ],
                'language-french' => [
                    'title' => 'Première image',
                ],
            ],
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-bdsm-domination',
                'story-theme-extreme',
            ],
        ],
        'story-image-second' => [
            'id' => '59dcc4de-6b72-487d-a266-d7f5b5059744',
            'reference' => 'Second image',
            'translations' => [
                'language-english' => [
                    'title' => 'Second image',
                ],
                'language-french' => [
                    'title' => 'Deuxième image',
                ],
            ],
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-oral-sex',
                'story-theme-soft',
            ],
        ],
        'story-image-third' => [
            'id' => 'c3b0bb1d-9c36-4a16-92df-1a103f87899b',
            'reference' => 'Third image',
            'translations' => [
                'language-english' => [
                    'title' => 'Third image',
                ],
                'language-french' => [
                    'title' => 'Troisième image',
                ],
            ],
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-sodomy',
                'story-theme-extreme',
            ],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $storyImageReference => $data) {
            $storyImage = (new StoryImage())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setReference($data['reference'])
            ;

            foreach ($data['translations'] as $languageReference => $translation) {
                (new StoryImageTranslation())
                    ->setTitle($translation['title'])
                    ->setStoryImage($storyImage)
                    ->setLanguage($this->getReference($languageReference))
                ;
            }

            foreach ($data['story_themes'] as $storyThemesReference) {
                (new StoryImageHasStoryTheme())
                    ->setStoryImage($storyImage)
                    ->setStoryTheme($this->getReference($storyThemesReference))
                ;
            }

            $manager->persist($storyImage);
            $this->addReference($storyImageReference, $storyImage);
        }

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
