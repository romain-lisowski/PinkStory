<?php

namespace App\Fixture\Language;

use App\Language\Domain\Model\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class LanguageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $language = (new Language())
            ->setId(Uuid::fromString('f11a8fd7-2a35-4f8a-a485-ab24acf214c1')->toRfc4122())
            ->setTitle('English')
            ->setLocale('en')
        ;
        $manager->persist($language);
        $this->addReference('language-english', $language);

        $language = (new Language())
            ->setId(Uuid::fromString('9854df32-4a08-4f10-93ed-ae72ce52748b')->toRfc4122())
            ->setTitle('Français')
            ->setLocale('fr')
        ;
        $manager->persist($language);
        $this->addReference('language-french', $language);

        $language = (new Language())
            ->setId(Uuid::fromString('99e8cc58-db0d-4ffd-9186-5a3f8c9e94e1')->toRfc4122())
            ->setTitle('Español')
            ->setLocale('es')
        ;
        $manager->persist($language);
        $this->addReference('language-spanish', $language);

        $language = (new Language())
            ->setId(Uuid::fromString('47afc681-9a6d-4fef-812e-f9df9a869945')->toRfc4122())
            ->setTitle('Italiano')
            ->setLocale('it')
        ;
        $manager->persist($language);
        $this->addReference('language-italian', $language);

        $manager->flush();
    }
}
