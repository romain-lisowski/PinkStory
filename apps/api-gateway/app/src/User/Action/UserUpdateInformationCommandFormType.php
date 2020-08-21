<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Command\UserUpdateInformationCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserUpdateInformationCommandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserUpdateInformationCommand::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
