<?php

namespace App\Fixture\User;

use App\User\Entity\User;
use App\User\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->rename('Yannis')
            ->updateEmail('hello@yannissgarra.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
            ->setRole(UserRole::ROLE_GOD)
        ;
        $manager->persist($user);
        $this->addReference('user-yannis', $user);

        $user = new User();
        $user->rename('Romain')
            ->updateEmail('romain.lisowski@gmail.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
            ->setRole(UserRole::ROLE_GOD)
        ;
        $manager->persist($user);
        $this->addReference('user-romain', $user);

        $user = new User();
        $user->rename('Leslie')
            ->updateEmail('leslie.akindou@gmail.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);
        $this->addReference('user-leslie', $user);

        $user = new User();
        $user->rename('Juliette')
            ->updateEmail('contact@julietteverdurand.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);
        $this->addReference('user-juliette', $user);

        $manager->flush();
    }
}
