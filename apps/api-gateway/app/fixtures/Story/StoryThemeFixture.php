<?php

namespace App\Fixture\Story;

use App\Fixture\Language\LanguageFixture;
use App\Story\Entity\StoryTheme;
use App\Story\Entity\StoryThemeTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class StoryThemeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storyThemeParent = new StoryTheme('Orientation');
        new StoryThemeTranslation('Orientation', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Orientation', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Heterosexual', $storyThemeParent);
        new StoryThemeTranslation('Heterosexual', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Hétérosexuel', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-heterosexual', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Gay', $storyThemeParent);
        new StoryThemeTranslation('LGBT - Gay', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('LGBT - Gay', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-gay', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Lesbian', $storyThemeParent);
        new StoryThemeTranslation('LGBT - Lesbian', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('LGBT - Lesbien', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-lesbian', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Bisexual', $storyThemeParent);
        new StoryThemeTranslation('LGBT - Bisexual', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('LGBT - Bissexuel', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-bisexual', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Other LGBT+', $storyThemeParent);
        new StoryThemeTranslation('Other LGBT+', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Autre LGBT+', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-other-lgbt', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Place');
        new StoryThemeTranslation('Place', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Lieu', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('At home', $storyThemeParent);
        new StoryThemeTranslation('At home', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('À la maison', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-home', $storyThemeChild);

        $storyThemeChild = new StoryTheme('At office', $storyThemeParent);
        new StoryThemeTranslation('At office', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Au bureau', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-office', $storyThemeChild);

        $storyThemeChild = new StoryTheme('In a public place', $storyThemeParent);
        new StoryThemeTranslation('In a public place', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Dans un lieu public', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-public', $storyThemeChild);

        $storyThemeChild = new StoryTheme('In nature', $storyThemeParent);
        new StoryThemeTranslation('In nature', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('En pleine nature', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-nature', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Number');
        new StoryThemeTranslation('Number', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Nombre', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Single', $storyThemeParent);
        new StoryThemeTranslation('Single', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Solitaire', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-single', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Couple', $storyThemeParent);
        new StoryThemeTranslation('Couple', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Couple', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-couple', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Threesome', $storyThemeParent);
        new StoryThemeTranslation('Threesome', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Triolisme', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-threesome', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Group', $storyThemeParent);
        new StoryThemeTranslation('Group', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Groupe', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-group', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Age');
        new StoryThemeTranslation('Age', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Âge', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Young', $storyThemeParent);
        new StoryThemeTranslation('Young', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Jeune', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-young', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Milf/Dilf', $storyThemeParent);
        new StoryThemeTranslation('Milf/Dilf', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Milf/Dilf', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-milf-dilf', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Mature', $storyThemeParent);
        new StoryThemeTranslation('Mature', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Mature', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-mature', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Pratice');
        new StoryThemeTranslation('Pratice', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Pratique', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Masturbation', $storyThemeParent);
        new StoryThemeTranslation('Masturbation', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Masturbation', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-masturbation', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Oral sex', $storyThemeParent);
        new StoryThemeTranslation('Oral sex', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Sexe oral', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-oral-sex', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Sodomy', $storyThemeParent);
        new StoryThemeTranslation('Sodomy', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Sodomie', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-sodomy', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Erotic game', $storyThemeParent);
        new StoryThemeTranslation('Erotic game', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Jeu érotique', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-erotic-game', $storyThemeChild);

        $storyThemeChild = new StoryTheme('BDSM - Domination', $storyThemeParent);
        new StoryThemeTranslation('BDSM - Domination', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('BDSM - Domination', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-bdsm-domination', $storyThemeChild);

        $storyThemeChild = new StoryTheme('BDSM - Submission', $storyThemeParent);
        new StoryThemeTranslation('BDSM - Submission', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('BDSM - Soumission', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-bdsm-submission', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Other BDSM', $storyThemeParent);
        new StoryThemeTranslation('Other BDSM', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Autre BDSM', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-other-bdsm', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Mores');
        new StoryThemeTranslation('Mores', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Moeurs', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Fetichism', $storyThemeParent);
        new StoryThemeTranslation('Fetichism', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Fétichisme', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-fetishism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Swapping', $storyThemeParent);
        new StoryThemeTranslation('Swapping', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Échangisme', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-swapping', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Libertinism', $storyThemeParent);
        new StoryThemeTranslation('Libertinism', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Libertinage', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-libertinism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Exhibitionism', $storyThemeParent);
        new StoryThemeTranslation('Exhibitionism', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Exhibitionnisme', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-exhibitionism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Voyeurism', $storyThemeParent);
        new StoryThemeTranslation('Voyeurism', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Voyeurisme', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-voyeurism', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Level');
        new StoryThemeTranslation('Level', $storyThemeParent, $this->getReference('language-english'));
        new StoryThemeTranslation('Niveau', $storyThemeParent, $this->getReference('language-french'));
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Soft', $storyThemeParent);
        new StoryThemeTranslation('Soft', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Soft', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-soft', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Hard', $storyThemeParent);
        new StoryThemeTranslation('Hard', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Hard', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-hard', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Extrême', $storyThemeParent);
        new StoryThemeTranslation('Extreme', $storyThemeChild, $this->getReference('language-english'));
        new StoryThemeTranslation('Extrême', $storyThemeChild, $this->getReference('language-french'));
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-extreme', $storyThemeChild);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LanguageFixture::class,
        ];
    }
}
