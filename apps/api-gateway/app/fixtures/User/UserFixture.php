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
            ->updateEmail('auth@yannissgarra.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
            ->setRole(UserRole::ROLE_GOD)
        ;
        $manager->persist($user);

        $user = new User();
        $user->rename('John')
            ->updateEmail('john@gmail.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);

        $user = new User();
        $user->rename('Jane')
            ->updateEmail('jane@gmail.com')
            ->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
