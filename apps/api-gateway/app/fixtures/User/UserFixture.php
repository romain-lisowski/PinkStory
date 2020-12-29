<?php

namespace App\Fixture\User;

use App\User\Model\Entity\User;
use App\User\Model\UserGender;
use App\User\Model\UserRole;
use App\User\Model\UserStatus;
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
        $user = new User('PinkStory', UserGender::UNDEFINED, 'hello@pinkstory.io', UserRole::ROLE_GOD, UserStatus::ACTIVATED, $this->getReference('language-french'));
        $user->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'));
        $manager->persist($user);
        $this->addReference('user-pinkstory', $user);

        $user = new User('Yannis', UserGender::MALE, 'hello@yannissgarra.com', UserRole::ROLE_GOD, UserStatus::ACTIVATED, $this->getReference('language-french'));
        $user->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'));
        $manager->persist($user);
        $this->addReference('user-yannis', $user);

        $user = new User('Romain', UserGender::MALE, 'romain.lisowski@gmail.com', UserRole::ROLE_GOD, UserStatus::ACTIVATED, $this->getReference('language-french'));
        $user->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'));
        $manager->persist($user);
        $this->addReference('user-romain', $user);

        $user = new User('Leslie', UserGender::FEMALE, 'leslie.akindou@gmail.com', UserRole::ROLE_MODERATOR, UserStatus::ACTIVATED, $this->getReference('language-french'));
        $user->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'));
        $manager->persist($user);
        $this->addReference('user-leslie', $user);

        $user = new User('Juliette', UserGender::FEMALE, 'contact@julietteverdurand.com', UserRole::ROLE_EDITOR, UserStatus::ACTIVATED, $this->getReference('language-french'));
        $user->updatePassword($this->passwordEncoder->encodePassword($user, '@Password2!'));
        $manager->persist($user);
        $this->addReference('user-juliette', $user);

        $manager->flush();
    }
}
