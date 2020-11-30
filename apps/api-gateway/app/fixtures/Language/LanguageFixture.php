<?php

namespace App\Fixture\Language;

use App\Language\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class LanguageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $language = new Language('English', 'en');
        $manager->persist($language);
        $this->addReference('language-english', $language);

        $language = new Language('Français', 'fr');
        $manager->persist($language);
        $this->addReference('language-french', $language);

        $language = new Language('Español', 'es');
        $manager->persist($language);
        $this->addReference('language-spanish', $language);

        $manager->flush();
    }
}
