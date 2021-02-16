<?php

namespace App\Fixture\User;

use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setId(Uuid::fromString('dc8d7267-fcb8-4f42-b164-a08e7cb9296b')->toRfc4122())
            ->setGender(UserGender::UNDEFINED)
            ->setName('Pinkstory')
            ->setEmail('hello@pinkstory.io')
            ->setPassword('@Password2!', $this->passwordEncoder)
            ->setImageDefined(false)
            ->setRole(UserRole::GOD)
            ->setStatus(UserStatus::ACTIVATED)
        ;
        $manager->persist($user);
        $this->addReference('user-pinkstory', $user);

        $user = (new User())
            ->setId(Uuid::fromString('1152313b-9b15-4f76-9618-1c6f56c07d1b')->toRfc4122())
            ->setGender(UserGender::MALE)
            ->setName('Yannis')
            ->setEmail('hello@yannissgarra.com')
            ->setPassword('@Password2!', $this->passwordEncoder)
            ->setImageDefined(false)
            ->setRole(UserRole::GOD)
            ->setStatus(UserStatus::ACTIVATED)
        ;
        $manager->persist($user);
        $this->addReference('user-yannis', $user);

        $user = (new User())
            ->setId(Uuid::fromString('0a5352ed-6989-4fec-9f9a-b96829850e6d')->toRfc4122())
            ->setGender(UserGender::MALE)
            ->setName('Romain')
            ->setEmail('romain.lisowski@gmail.com')
            ->setPassword('@Password2!', $this->passwordEncoder)
            ->setImageDefined(false)
            ->setRole(UserRole::GOD)
            ->setStatus(UserStatus::ACTIVATED)
        ;
        $manager->persist($user);
        $this->addReference('user-romain', $user);

        $user = (new User())
            ->setId(Uuid::fromString('d50be9fc-0b87-4faa-afea-24fa2491e236')->toRfc4122())
            ->setGender(UserGender::FEMALE)
            ->setName('Leslie')
            ->setEmail('leslie.akindou@gmail.com')
            ->setPassword('@Password2!', $this->passwordEncoder)
            ->setImageDefined(false)
            ->setRole(UserRole::GOD)
            ->setStatus(UserStatus::ACTIVATED)
        ;
        $manager->persist($user);
        $this->addReference('user-leslie', $user);

        $user = (new User())
            ->setId(Uuid::fromString('162481c3-4b9e-4f8d-9451-768205f6c7a6')->toRfc4122())
            ->setGender(UserGender::FEMALE)
            ->setName('Juliette')
            ->setEmail('contact@julietteverdurand.com')
            ->setPassword('@Password2!', $this->passwordEncoder)
            ->setImageDefined(false)
            ->setRole(UserRole::GOD)
            ->setStatus(UserStatus::ACTIVATED)
        ;
        $manager->persist($user);
        $this->addReference('user-juliette', $user);

        $manager->flush();
    }
}
