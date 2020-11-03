<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Command\UserRegeneratePasswordForgottenSecretCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserRegeneratePasswordForgottenSecretCommandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegeneratePasswordForgottenSecretCommand::class,
            'method' => Request::METHOD_PATCH,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
