<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Command\AbstractCommandFormType;
use App\Command\CommandFormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserUpdateImageCommandFormType extends AbstractCommandFormType implements CommandFormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'method' => Request::METHOD_PATCH,
        ]);
    }
}
