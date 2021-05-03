<?php

namespace App\Fixture\User;

use App\Fixture\Language\LanguageFixture;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'user-pinkstory' => [
            'id' => 'dc8d7267-fcb8-4f42-b164-a08e7cb9296b',
            'gender' => UserGender::UNDEFINED,
            'name' => 'Pinkstory',
            'email' => 'hello@pinkstory.io',
            'password' => '@Password2!',
            'role' => UserRole::GOD,
            'language_reference' => 'language-french',
            'reading_language_references' => [
                'language-english',
                'language-french',
            ],
        ],
        'user-yannis' => [
            'id' => '1152313b-9b15-4f76-9618-1c6f56c07d1b',
            'gender' => UserGender::MALE,
            'name' => 'Yannis',
            'email' => 'hello@yannissgarra.com',
            'password' => '@Password2!',
            'role' => UserRole::ADMIN,
            'language_reference' => 'language-french',
            'reading_language_references' => [
                'language-english',
                'language-french',
            ],
        ],
        'user-romain' => [
            'id' => '0a5352ed-6989-4fec-9f9a-b96829850e6d',
            'gender' => UserGender::MALE,
            'name' => 'Romain',
            'email' => 'romain.lisowski@gmail.com',
            'password' => '@Password2!',
            'role' => UserRole::ADMIN,
            'language_reference' => 'language-french',
            'reading_language_references' => [
                'language-english',
                'language-french',
            ],
        ],
        'user-leslie' => [
            'id' => 'd50be9fc-0b87-4faa-afea-24fa2491e236',
            'gender' => UserGender::FEMALE,
            'name' => 'Leslie',
            'email' => 'leslie.akindou@gmail.com',
            'password' => '@Password2!',
            'role' => UserRole::MODERATOR,
            'language_reference' => 'language-french',
            'reading_language_references' => [
                'language-french',
            ],
        ],
        'user-juliette' => [
            'id' => '162481c3-4b9e-4f8d-9451-768205f6c7a6',
            'gender' => UserGender::FEMALE,
            'name' => 'Juliette',
            'email' => 'contact@julietteverdurand.com',
            'password' => '@Password2!',
            'role' => UserRole::USER,
            'language_reference' => 'language-french',
            'reading_language_references' => [
                'language-english',
                'language-french',
            ],
        ],
        'user-john' => [
            'id' => '1bd26cfe-7e85-401c-b379-65158bfca161',
            'gender' => UserGender::MALE,
            'name' => 'John',
            'email' => 'john@pinkstory.io',
            'password' => '@Password2!',
            'role' => UserRole::USER,
            'language_reference' => 'language-english',
            'reading_language_references' => [
                'language-english',
            ],
        ],
    ];

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $reference => $data) {
            $user = (new User())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setGender($data['gender'])
                ->setName($data['name'])
                ->setEmail($data['email'])
                ->validateEmail()
                ->setPassword($data['password'], $this->passwordEncoder)
                ->setImageDefined(false)
                ->setRole($data['role'])
                ->setStatus(UserStatus::ACTIVATED)
                ->setLanguage($this->getReference($data['language_reference']))
            ;

            foreach ($data['reading_language_references'] as $readingLanguageReference) {
                $user->addReadingLanguage($this->getReference($readingLanguageReference));
            }

            $manager->persist($user);
            $this->addReference($reference, $user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LanguageFixture::class,
        ];
    }
}
