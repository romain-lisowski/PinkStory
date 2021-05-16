<?php

namespace App\Fixture\Language;

use App\Language\Domain\Model\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class LanguageFixture extends Fixture
{
    public const DATA = [
        'language-english' => [
            'id' => 'f11a8fd7-2a35-4f8a-a485-ab24acf214c1',
            'title' => 'English',
            'locale' => 'en',
        ],
        'language-spanish' => [
            'id' => '99e8cc58-db0d-4ffd-9186-5a3f8c9e94e1',
            'title' => 'Español',
            'locale' => 'es',
        ],
        'language-french' => [
            'id' => '9854df32-4a08-4f10-93ed-ae72ce52748b',
            'title' => 'Français',
            'locale' => 'fr',
        ],
        'language-italian' => [
            'id' => '47afc681-9a6d-4fef-812e-f9df9a869945',
            'title' => 'Italiano',
            'locale' => 'it',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $reference => $data) {
            $language = (new Language())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setTitle($data['title'])
                ->setLocale($data['locale'])
            ;
            $manager->persist($language);
            $this->addReference($reference, $language);
        }

        $manager->flush();
    }
}
