<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Command\UserUpdateImageCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserUpdateImageCommandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserUpdateImageCommand::class,
            'method' => Request::METHOD_PATCH,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
