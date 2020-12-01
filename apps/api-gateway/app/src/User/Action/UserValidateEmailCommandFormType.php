<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Command\UserValidateEmailCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserValidateEmailCommandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserValidateEmailCommand::class,
            'method' => Request::METHOD_PATCH,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
