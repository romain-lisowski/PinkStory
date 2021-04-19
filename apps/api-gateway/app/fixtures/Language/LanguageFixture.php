<?php

namespace App\Fixture\Language;

use App\Language\Domain\Model\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class LanguageFixture extends Fixture
{
    public const DATA = [
        [
            'id' => 'f11a8fd7-2a35-4f8a-a485-ab24acf214c1',
            'title' => 'English',
            'locale' => 'en',
            'reference' => 'language-english',
        ], [
            'id' => '99e8cc58-db0d-4ffd-9186-5a3f8c9e94e1',
            'title' => 'Español',
            'locale' => 'es',
            'reference' => 'language-spanish',
        ], [
            'id' => '9854df32-4a08-4f10-93ed-ae72ce52748b',
            'title' => 'Français',
            'locale' => 'fr',
            'reference' => 'language-french',
        ], [
            'id' => '47afc681-9a6d-4fef-812e-f9df9a869945',
            'title' => 'Italiano',
            'locale' => 'it',
            'reference' => 'language-italian',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $data) {
            $language = (new Language())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setTitle($data['title'])
                ->setLocale($data['locale'])
            ;
            $manager->persist($language);
            $this->addReference($data['reference'], $language);
        }

        $manager->flush();
    }
}
