<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandFormType;
use App\Command\CommandFormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserCreateCommandFormType extends AbstractCommandFormType implements CommandFormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'user.validator.password_match',
            ])
            ->add('image', FileType::class, [
                'required' => false,
            ])
        ;
    }
}
