<?php

namespace App\Fixture\User;

use App\User\Domain\Model\AccessToken;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AccessTokenFixture extends Fixture implements DependentFixtureInterface
{
    public const DATA = [
        'access-token-pinkstory' => [
            'id' => 'f478da1e-f5a8-4c28-a5e2-77abeb7f1cdf',
            'user_reference' => 'user-pinkstory',
        ],
        'access-token-yannis' => [
            'id' => '70dae0b1-86c6-4fee-9106-5639b5fa588a',
            'user_reference' => 'user-yannis',
        ],
        'access-token-romain' => [
            'id' => '8f1f0011-ed7f-46c1-aad2-a80695a5f636',
            'user_reference' => 'user-romain',
        ],
        'access-token-leslie' => [
            'id' => 'd42e7e5f-f786-4eff-abd0-b58576491f9a',
            'user_reference' => 'user-leslie',
        ],
        'access-token-juliette' => [
            'id' => '8b134166-af5c-41b6-8193-a6d901e3544a',
            'user_reference' => 'user-juliette',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DATA as $reference => $data) {
            $token = (new AccessToken())
                ->setId(Uuid::fromString($data['id'])->toRfc4122())
                ->setUser($this->getReference($data['user_reference']))
            ;
            $manager->persist($token);
            $this->addReference($reference, $token);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}
