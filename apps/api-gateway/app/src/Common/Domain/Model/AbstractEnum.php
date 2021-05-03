<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

use App\Common\Domain\Translation\TranslatorInterface;

abstract class AbstractEnum
{
    protected static string $translationPrefix = 'value.';

    public static function getTranslationPrefix(): string
    {
        return static::$translationPrefix;
    }

    public static function getChoices(): array
    {
        return array_values(static::getConstants());
    }

    public static function getReadingChoices(TranslatorInterface $translator): array
    {
        $constants = static::getConstants();

        array_walk($constants, function (&$value, &$key) use ($translator) {
            $value = static::getReadingChoice($value, $translator);
        });

        return $constants;
    }

    public static function getReadingChoice(string $value, TranslatorInterface $translator): string
    {
        return $translator->trans(strtolower(static::$translationPrefix.$value));
    }

    protected static function getConstants(): array
    {
        $enum = new \ReflectionClass(static::class);

        return $enum->getConstants();
    }
}
