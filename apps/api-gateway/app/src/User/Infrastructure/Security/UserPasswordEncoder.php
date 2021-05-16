<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Model\User;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class UserPasswordEncoder implements UserPasswordEncoderInterface
{
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function encodePassword(string $plainPassword): string
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);

        return $encoder->encodePassword($plainPassword, null);
    }

    public function isPasswordValid(User $user, string $plainPassword): bool
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);

        return $encoder->isPasswordValid($user->getPassword(), $plainPassword, null);
    }
}
