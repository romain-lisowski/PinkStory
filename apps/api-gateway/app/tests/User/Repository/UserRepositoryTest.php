<?php

declare(strict_types=1);

namespace App\Test\User\Repository;

use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::$container->get('doctrine')->getManager();

        // need to get repository by doctrine cause it's been removed from container as a private service
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testFindOneSucess(): void
    {
        $uuid = Uuid::v4();
        $newUser = new User();
        $newUser->setId($uuid->toRfc4122())
            ->setName('Test')
            ->setEmail('test@gmail.com')
            ->setPassword('non_encoded_password')
        ;
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        $user = $this->userRepository->findOne($uuid->toRfc4122());

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getId(), $uuid->toRfc4122());
        $this->assertEquals($user->getEmail(), 'test@gmail.com');
    }

    public function testFindOneFail(): void
    {
        $this->expectException(NoResultException::class);

        $user = $this->userRepository->findOne(Uuid::v4()->toRfc4122());
    }

    public function testFindOneByEmailSucess(): void
    {
        $user = $this->userRepository->findOneByEmail('john@gmail.com');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getName(), 'John');
        $this->assertEquals($user->getEmail(), 'john@gmail.com');
    }

    public function testFindOneByEmailFail(): void
    {
        $this->expectException(NoResultException::class);

        $user = $this->userRepository->findOneByEmail('john2@gmail.com');
    }

    public function testFindOneByNotUsedEmailValidationSecretSucess(): void
    {
        $newUser = new User();
        $newUser->setName('Test')
            ->setEmail('test@gmail.com')
            ->setPassword('non_encoded_password')
        ;
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        $user = $this->userRepository->findOneByNotUsedEmailValidationSecret($newUser->getEmailValidationSecret());

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getId(), $newUser->getId());
    }

    public function testFindOneByNotUsedEmailValidationSecretFailWrongSecret(): void
    {
        $this->expectException(NoResultException::class);

        $user = $this->userRepository->findOneByNotUsedEmailValidationSecret(Uuid::v4()->toRfc4122());
    }

    public function testFindOneByNotUsedEmailValidationSecretFailUsedSecret(): void
    {
        $newUser = new User();
        $newUser->setName('Test')
            ->setEmail('test@gmail.com')
            ->setPassword('non_encoded_password')
            ->setEmailValidationSecretUsed(true)
        ;
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        $this->expectException(NoResultException::class);

        $user = $this->userRepository->findOneByNotUsedEmailValidationSecret($newUser->getEmailValidationSecret());
    }
}
