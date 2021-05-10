<?php

namespace App\Fixture\Story;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\UserFixture;
use App\Story\Domain\Model\Story;
use App\Story\Domain\Model\StoryHasStoryTheme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class StoryFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'story-first' => [
            'id' => '067d92b2-fd2a-4815-9d75-bf7dee31aec8',
            'title' => 'First story',
            'content' => 'Content of the first story',
            'extract' => 'Extract of the first story',
            'user_reference' => 'user-john',
            'language_reference' => 'language-english',
            'story_image_reference' => 'story-image-first',
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-office',
                'story-theme-threesome',
                'story-theme-bdsm-domination',
                'story-theme-extreme',
            ],
        ],
        'story-second' => [
            'id' => '5f2a7bdb-f9aa-4bb0-9b02-0f1b751d4d6d',
            'title' => 'Second story',
            'content' => 'Content of the second story',
            'extract' => 'Extract of the second story',
            'user_reference' => 'user-john',
            'language_reference' => 'language-english',
            'story_image_reference' => 'story-image-second',
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-office',
            ],
            'children' => [
                'story-second-first' => [
                    'id' => '55476103-f142-4f38-bd33-4b5348ea9d2d',
                    'title' => 'First chapter of the second story',
                    'content' => 'Content of the first chapter of the second story',
                    'extract' => 'Extract of the first chapter of the second story',
                    'user_reference' => 'user-john',
                    'language_reference' => 'language-english',
                    'story_image_reference' => 'story-image-second',
                    'story_themes' => [
                        'story-theme-heterosexual',
                        'story-theme-office',
                        'story-theme-oral-sex',
                        'story-theme-soft',
                    ],
                ],
                'story-second-second' => [
                    'id' => 'c889cb01-0992-45cf-857e-ffeabb318b20',
                    'title' => 'Second chapter of the second story',
                    'content' => 'Content of the second chapter of the second story',
                    'extract' => 'Extract of the second chapter of the second story',
                    'user_reference' => 'user-john',
                    'language_reference' => 'language-english',
                    'story_image_reference' => 'story-image-third',
                    'story_themes' => [
                        'story-theme-heterosexual',
                        'story-theme-home',
                        'story-theme-sodomy',
                        'story-theme-hard',
                    ],
                ],
            ],
        ],
        'story-third' => [
            'id' => 'a89d68e2-e0a4-4b31-b04c-d2b2ccb6857e',
            'title' => 'Troisième histoire',
            'content' => 'Contenu de la troisième histoire',
            'extract' => 'Extrait de la troisième histoire',
            'user_reference' => 'user-leslie',
            'language_reference' => 'language-french',
            'story_image_reference' => 'story-image-first',
            'story_themes' => [
                'story-theme-heterosexual',
                'story-theme-office',
                'story-theme-threesome',
                'story-theme-bdsm-domination',
                'story-theme-extreme',
            ],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $this->save($manager, self::DATA);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LanguageFixture::class,
            StoryThemeFixture::class,
            StoryImageFixture::class,
            UserFixture::class,
        ];
    }

    private function save(ObjectManager $manager, array $storyData, ?Story $parent = null): void
    {
        foreach ($storyData as $storyReference => $data) {
            $story = (new Story())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setTitle($data['title'])
                ->setContent($data['content'])
                ->setExtract($data['extract'])
                ->setUser($this->getReference($data['user_reference']))
                ->setLanguage($this->getReference($data['language_reference']))
                ->setStoryImage($this->getReference($data['story_image_reference']))
            ;

            if (null !== $parent) {
                $story->setParent($parent);
            }

            foreach ($data['story_themes'] as $storyThemesReference) {
                (new StoryHasStoryTheme())
                    ->setStory($story)
                    ->setStoryTheme($this->getReference($storyThemesReference))
                ;
            }

            $manager->persist($story);
            $this->addReference($storyReference, $story);

            if (true === isset($data['children'])) {
                $this->save($manager, $data['children'], $story);
            }
        }
    }
}
