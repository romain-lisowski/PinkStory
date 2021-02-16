<?php

namespace App\Fixture\User;

use App\User\Domain\Model\AccessToken;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AccessTokenFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $token = (new AccessToken())
            ->setId(Uuid::fromString('f478da1e-f5a8-4c28-a5e2-77abeb7f1cdf')->toRfc4122())
            ->setUser($this->getReference('user-pinkstory'))
        ;
        $manager->persist($token);

        $token = (new AccessToken())
            ->setId(Uuid::fromString('70dae0b1-86c6-4fee-9106-5639b5fa588a')->toRfc4122())
            ->setUser($this->getReference('user-yannis'))
        ;
        $manager->persist($token);

        $token = (new AccessToken())
            ->setId(Uuid::fromString('8f1f0011-ed7f-46c1-aad2-a80695a5f636')->toRfc4122())
            ->setUser($this->getReference('user-romain'))
        ;
        $manager->persist($token);

        $token = (new AccessToken())
            ->setId(Uuid::fromString('d42e7e5f-f786-4eff-abd0-b58576491f9a')->toRfc4122())
            ->setUser($this->getReference('user-leslie'))
        ;
        $manager->persist($token);

        $token = (new AccessToken())
            ->setId(Uuid::fromString('8b134166-af5c-41b6-8193-a6d901e3544a')->toRfc4122())
            ->setUser($this->getReference('user-juliette'))
        ;
        $manager->persist($token);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}
