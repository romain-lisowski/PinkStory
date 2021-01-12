<?php

declare(strict_types=1);

namespace App\Story\Command;

use App\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class StoryCreateCommandFormType extends AbstractFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('extract', TextType::class)
            ->add('story_image_id', TextType::class, [
                'property_path' => 'storyImageId',
                'required' => false,
            ])
            ->add('language_id', TextType::class, [
                'property_path' => 'languageId',
            ])
            ->add('story_theme_ids', CollectionType::class, [
                'property_path' => 'storyThemeIds',
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
            ])
        ;
    }
}
