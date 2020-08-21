<?php

namespace App\Fixture\User;

use App\User\Entity\User;
use App\User\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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
            ->changeEmail('auth@yannissgarra.com')
            ->changePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
            ->setRole(UserRole::ROLE_GOD)
        ;
        $manager->persist($user);

        $user = new User();
        $user->rename('John')
            ->changeEmail('john@gmail.com')
            ->changePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);

        $user = new User();
        $user->rename('Jane')
            ->changeEmail('jane@gmail.com')
            ->changePassword($this->passwordEncoder->encodePassword($user, '@Password2!'))
        ;
        $manager->persist($user);

        $manager->flush();
    }
}
