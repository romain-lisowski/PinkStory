<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandFormType;
use App\Command\CommandFormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserGenerateAuthTokenCommandFormType extends AbstractCommandFormType implements CommandFormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
        ;
    }
}
