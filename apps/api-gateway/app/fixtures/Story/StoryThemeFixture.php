<?php

namespace App\Fixture\Story;

use App\Fixture\Language\LanguageFixture;
use App\Story\Domain\Model\StoryTheme;
use App\Story\Domain\Model\StoryThemeTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class StoryThemeFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'story-theme-orientation' => [
            'id' => '607d3f04-b32f-4f3f-bc91-d52dc73890fa',
            'reference' => 'Orientation',
            'position' => 1,
            'translations' => [
                'language-english' => [
                    'title' => 'Orientation',
                ],
                'language-french' => [
                    'title' => 'Orientation',
                ],
            ],
            'children' => [
                'story-theme-heterosexual' => [
                    'id' => 'b4bf57f4-3fa9-4720-9bc8-2a931cbc0db5',
                    'reference' => 'Heterosexual',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Heterosexual',
                        ],
                        'language-french' => [
                            'title' => 'Hétérosexuel',
                        ],
                    ],
                ],
                'story-theme-lgbt-gay' => [
                    'id' => 'b0736ee2-7c53-4040-8dba-7b3c0ffd140e',
                    'reference' => 'LGBT - Gay',
                    'translations' => [
                        'language-english' => [
                            'title' => 'LGBT - Gay',
                        ],
                        'language-french' => [
                            'title' => 'LGBT - Gay',
                        ],
                    ],
                ],
                'story-theme-lgbt-lesbian' => [
                    'id' => '7adc9b1c-df59-48b0-8677-f5b81d89c6fb',
                    'reference' => 'LGBT - Lesbian',
                    'translations' => [
                        'language-english' => [
                            'title' => 'LGBT - Lesbian',
                        ],
                        'language-french' => [
                            'title' => 'LGBT - Lesbien',
                        ],
                    ],
                ],
                'story-theme-lgbt-bisexual' => [
                    'id' => '88096c86-874d-44f9-96b1-e22edc4b5a5f',
                    'reference' => 'LGBT - Bisexual',
                    'translations' => [
                        'language-english' => [
                            'title' => 'LGBT - Bisexual',
                        ],
                        'language-french' => [
                            'title' => 'LGBT - Bissexuel',
                        ],
                    ],
                ],
                'story-theme-other-lgbt' => [
                    'id' => '794cc041-cf1e-44bb-9427-85e8eaf057c9',
                    'reference' => 'Other LGBT+',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Other LGBT+',
                        ],
                        'language-french' => [
                            'title' => 'Autre LGBT+',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-place' => [
            'id' => 'e8b57d20-7a11-4c24-8982-870869c00d1a',
            'reference' => 'Place',
            'position' => 2,
            'translations' => [
                'language-english' => [
                    'title' => 'Place',
                ],
                'language-french' => [
                    'title' => 'Lieu',
                ],
            ],
            'children' => [
                'story-theme-home' => [
                    'id' => '0cf2481d-58a0-48a1-a244-95d323a7d412',
                    'reference' => 'At home',
                    'translations' => [
                        'language-english' => [
                            'title' => 'At home',
                        ],
                        'language-french' => [
                            'title' => 'À la maison',
                        ],
                    ],
                ],
                'story-theme-office' => [
                    'id' => '67ce8e06-e8ed-4bfe-b817-9d6c2c09cbf0',
                    'reference' => 'At office',
                    'translations' => [
                        'language-english' => [
                            'title' => 'At office',
                        ],
                        'language-french' => [
                            'title' => 'Au bureau',
                        ],
                    ],
                ],
                'story-theme-public' => [
                    'id' => '8978078b-5a04-4633-a81c-7fbd1aebe311',
                    'reference' => 'In a public place',
                    'translations' => [
                        'language-english' => [
                            'title' => 'In a public place',
                        ],
                        'language-french' => [
                            'title' => 'Dans un lieu public',
                        ],
                    ],
                ],
                'story-theme-nature' => [
                    'id' => '1c46508e-674a-4787-8ec2-22600e68af45',
                    'reference' => 'In nature',
                    'translations' => [
                        'language-english' => [
                            'title' => 'In nature',
                        ],
                        'language-french' => [
                            'title' => 'En pleine nature',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-number' => [
            'id' => 'c42ed58e-22eb-42c6-86f1-d43647d9fb97',
            'reference' => 'Number',
            'position' => 3,
            'translations' => [
                'language-english' => [
                    'title' => 'Number',
                ],
                'language-french' => [
                    'title' => 'Nombre',
                ],
            ],
            'children' => [
                'story-theme-single' => [
                    'id' => 'b0f15784-2bc7-412f-9c05-1bff5df74ffc',
                    'reference' => 'Single',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Single',
                        ],
                        'language-french' => [
                            'title' => 'Solitaire',
                        ],
                    ],
                ],
                'story-theme-couple' => [
                    'id' => '3a5c44f7-3b72-46bd-a629-d446d7e42f4a',
                    'reference' => 'Couple',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Couple',
                        ],
                        'language-french' => [
                            'title' => 'Couple',
                        ],
                    ],
                ],
                'story-theme-threesome' => [
                    'id' => '700b1c36-c12c-4f36-a64f-37def288cbc9',
                    'reference' => 'Threesome',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Threesome',
                        ],
                        'language-french' => [
                            'title' => 'Triolisme',
                        ],
                    ],
                ],
                'story-theme-group' => [
                    'id' => '5937b562-73fa-4cb1-9d97-242c07758949',
                    'reference' => 'Group',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Group',
                        ],
                        'language-french' => [
                            'title' => 'Groupe',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-age' => [
            'id' => '8f64efec-9cac-44d9-83e2-ed925d5031bf',
            'reference' => 'Age',
            'position' => 4,
            'translations' => [
                'language-english' => [
                    'title' => 'Age',
                ],
                'language-french' => [
                    'title' => 'Âge',
                ],
            ],
            'children' => [
                'story-theme-young' => [
                    'id' => '18ec202c-d32b-43e7-a691-b07cc7636214',
                    'reference' => 'Young',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Young',
                        ],
                        'language-french' => [
                            'title' => 'Jeune',
                        ],
                    ],
                ],
                'story-theme-milf-dilf' => [
                    'id' => 'a02b4396-7227-42d0-ab46-e134577ee75a',
                    'reference' => 'Milf/Dilf',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Milf/Dilf',
                        ],
                        'language-french' => [
                            'title' => 'Milf/Dilf',
                        ],
                    ],
                ],
                'story-theme-mature' => [
                    'id' => 'ac381b15-b02f-4866-bb74-9009029116f3',
                    'reference' => 'Mature',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Mature',
                        ],
                        'language-french' => [
                            'title' => 'Mature',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-practice' => [
            'id' => '2303301a-1633-45e3-8a0a-d7098ba4a076',
            'reference' => 'Practice',
            'position' => 5,
            'translations' => [
                'language-english' => [
                    'title' => 'Practice',
                ],
                'language-french' => [
                    'title' => 'Pratique',
                ],
            ],
            'children' => [
                'story-theme-masturbation' => [
                    'id' => 'fcb32e64-5518-4751-99e7-ed0803156dc2',
                    'reference' => 'Masturbation',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Masturbation',
                        ],
                        'language-french' => [
                            'title' => 'Masturbation',
                        ],
                    ],
                ],
                'story-theme-oral-sex' => [
                    'id' => '3c10a0d9-eea3-43d9-ae18-11a59b05d200',
                    'reference' => 'Oral sex',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Oral sex',
                        ],
                        'language-french' => [
                            'title' => 'Sexe oral',
                        ],
                    ],
                ],
                'story-theme-sodomy' => [
                    'id' => '77f3c288-0d36-4e1b-b6b3-718e27b6ba8c',
                    'reference' => 'Sodomy',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Sodomy',
                        ],
                        'language-french' => [
                            'title' => 'Sodomie',
                        ],
                    ],
                ],
                'story-theme-erotic-game' => [
                    'id' => '27b4d81f-f8aa-4cc4-94e9-a787ff105089',
                    'reference' => 'Erotic game',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Erotic game',
                        ],
                        'language-french' => [
                            'title' => 'Jeu érotique',
                        ],
                    ],
                ],
                'story-theme-bdsm-domination' => [
                    'id' => 'd43da482-c48a-47a4-ad02-c18f3a93b024',
                    'reference' => 'BDSM - Domination',
                    'translations' => [
                        'language-english' => [
                            'title' => 'BDSM - Domination',
                        ],
                        'language-french' => [
                            'title' => 'BDSM - Domination',
                        ],
                    ],
                ],
                'story-theme-bdsm-submission' => [
                    'id' => '9f6b7b75-06ea-4970-aa7c-ac479f69a062',
                    'reference' => 'BDSM - Submission',
                    'translations' => [
                        'language-english' => [
                            'title' => 'BDSM - Submission',
                        ],
                        'language-french' => [
                            'title' => 'BDSM - Soumission',
                        ],
                    ],
                ],
                'story-theme-other-bdsm' => [
                    'id' => '1745b279-94e5-43b3-bb43-982a971c32e2',
                    'reference' => 'Other BDSM',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Other BDSM',
                        ],
                        'language-french' => [
                            'title' => 'Autre BDSM',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-mores' => [
            'id' => 'ef0f4417-17af-4685-9fb3-75f39c6413e9',
            'reference' => 'Mores',
            'position' => 6,
            'translations' => [
                'language-english' => [
                    'title' => 'Mores',
                ],
                'language-french' => [
                    'title' => 'Moeurs',
                ],
            ],
            'children' => [
                'story-theme-fetichism' => [
                    'id' => '391930ca-3fc2-455f-be02-2c22607989d6',
                    'reference' => 'Fetichism',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Fetichism',
                        ],
                        'language-french' => [
                            'title' => 'Fétichisme',
                        ],
                    ],
                ],
                'story-theme-swapping' => [
                    'id' => 'a4bb1839-7b6e-4334-91e1-5cfbae1691ab',
                    'reference' => 'Swapping',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Swapping',
                        ],
                        'language-french' => [
                            'title' => 'Échangisme',
                        ],
                    ],
                ],
                'story-theme-libertinism' => [
                    'id' => '830ccbc7-f3c1-4178-b21d-beeaa334ce95',
                    'reference' => 'Libertinism',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Libertinism',
                        ],
                        'language-french' => [
                            'title' => 'Libertinage',
                        ],
                    ],
                ],
                'story-theme-exhibitionism' => [
                    'id' => '9053b320-586c-42f0-9fce-56f10b43f2dd',
                    'reference' => 'Exhibitionism',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Exhibitionism',
                        ],
                        'language-french' => [
                            'title' => 'Exhibitionnisme',
                        ],
                    ],
                ],
                'story-theme-voyeurism' => [
                    'id' => '3b9de815-6827-4593-9354-ab93d441ba4b',
                    'reference' => 'Voyeurism',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Voyeurism',
                        ],
                        'language-french' => [
                            'title' => 'Voyeurisme',
                        ],
                    ],
                ],
            ],
        ],
        'story-theme-level' => [
            'id' => '05c030a4-0352-484c-92c9-5db916115050',
            'reference' => 'Level',
            'position' => 7,
            'translations' => [
                'language-english' => [
                    'title' => 'Level',
                ],
                'language-french' => [
                    'title' => 'Niveau',
                ],
            ],
            'children' => [
                'story-theme-soft' => [
                    'id' => '11be5e75-c85f-4e27-9aed-1fbb7ded4a2d',
                    'reference' => 'Soft',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Soft',
                        ],
                        'language-french' => [
                            'title' => 'Soft',
                        ],
                    ],
                ],
                'story-theme-hard' => [
                    'id' => 'c1ceb1c0-8c19-4bf4-ace9-df5fc85efa0d',
                    'reference' => 'Hard',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Hard',
                        ],
                        'language-french' => [
                            'title' => 'Hard',
                        ],
                    ],
                ],
                'story-theme-extreme' => [
                    'id' => '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
                    'reference' => 'Extreme',
                    'translations' => [
                        'language-english' => [
                            'title' => 'Extreme',
                        ],
                        'language-french' => [
                            'title' => 'Extrême',
                        ],
                    ],
                ],
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
        ];
    }

    private function save(ObjectManager $manager, array $storyThemesData, ?StoryTheme $parent = null): void
    {
        foreach ($storyThemesData as $storyThemeReference => $data) {
            $storyTheme = (new StoryTheme())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setReference($data['reference'])
            ;

            if (null !== $parent) {
                $storyTheme->setParent($parent);
            }

            if (true === isset($data['position'])) {
                $storyTheme->setPosition($data['position']);
            }

            foreach ($data['translations'] as $languageReference => $translation) {
                (new StoryThemeTranslation())
                    ->setTitle($translation['title'])
                    ->setStoryTheme($storyTheme)
                    ->setLanguage($this->getReference($languageReference))
                ;
            }

            $manager->persist($storyTheme);
            $this->addReference($storyThemeReference, $storyTheme);

            if (true === isset($data['children'])) {
                $this->save($manager, $data['children'], $storyTheme);
            }
        }
    }
}
