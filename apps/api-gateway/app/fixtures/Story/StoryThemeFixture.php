<?php

namespace App\Fixture\Story;

use App\Story\Entity\StoryTheme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class StoryThemeFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $storyThemeParent = new StoryTheme('Orientation');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Hétérosexuel', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-heterosexual', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Gay', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-gay', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Lesbien', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-lesbian', $storyThemeChild);

        $storyThemeChild = new StoryTheme('LGBT - Bissexuel', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-lgbt-bisexual', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Autre LGBT+', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-other-lgbt', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Lieu');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('À la maison', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-home', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Au bureau', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-office', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Dans un lieu public', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-public', $storyThemeChild);

        $storyThemeChild = new StoryTheme('En pleine nature', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-nature', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Nombre');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Solitaire', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-single', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Couple', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-couple', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Triolisme', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-threesome', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Groupe', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-group', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Âge');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Jeune', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-young', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Milf/Dilf', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-milf-dilf', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Mature', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-mature', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Pratique');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Masturbation', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-masturbation', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Sexe oral', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-oral-sex', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Sodomie', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-sodomy', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Jeu érotique', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-erotic-game', $storyThemeChild);

        $storyThemeChild = new StoryTheme('BDSM - Domination', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-bdsm-domination', $storyThemeChild);

        $storyThemeChild = new StoryTheme('BDSM - Soumission', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-bdsm-submission', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Autre BDSM', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-other-bdsm', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Moeurs');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Fétichisme', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-fetishism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Échangisme', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-swapping', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Libertinage', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-libertinism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Exhibitionnisme', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-exhibitionism', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Voyeurisme', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-voyeurism', $storyThemeChild);

        $storyThemeParent = new StoryTheme('Niveau');
        $manager->persist($storyThemeParent);

        $storyThemeChild = new StoryTheme('Soft', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-soft', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Hard', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-hard', $storyThemeChild);

        $storyThemeChild = new StoryTheme('Extrême', $storyThemeParent);
        $manager->persist($storyThemeChild);
        $this->addReference('story-theme-extreme', $storyThemeChild);

        $manager->flush();
    }
}
