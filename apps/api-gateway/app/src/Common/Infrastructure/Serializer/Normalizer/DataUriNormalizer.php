<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Serializer\Normalizer;

use InvalidArgumentException;
use SplFileObject;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer as SerializerDataUriNormalizer;
use Throwable;

final class DataUriNormalizer extends SerializerDataUriNormalizer
{
    private const DATA_URI_REGEXP = '/^data:([a-z0-9][a-z0-9\!\#\$\&\-\^\_\+\.]{0,126}\/[a-z0-9][a-z0-9\!\#\$\&\-\^\_\+\.]{0,126}(;[a-z0-9\-]+\=[a-z0-9\-]+)?)?(;base64)?,([a-z0-9\!\$\&\\\'\,\(\)\*\+\,\;\=\-\.\_\~\:\@\/\?\%\s]*)\s*$/i';

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $slices = [];

        if (!preg_match(self::DATA_URI_REGEXP, $data, $slices)) {
            throw new NotNormalizableValueException('The provided "data:" URI is not valid.');
        }

        try {
            $tmp = sys_get_temp_dir().'/'.uniqid();
            file_put_contents($tmp, base64_decode($slices[4]));

            switch ($type) {
                case 'Symfony\Component\HttpFoundation\File\File':
                    if (!class_exists(File::class)) {
                        throw new InvalidArgumentException(sprintf('Cannot denormalize to a "%s" without the HttpFoundation component installed. Try running "composer require symfony/http-foundation".', File::class));
                    }

                    return new File($tmp, false);

                case 'SplFileObject':
                case 'SplFileInfo':
                    return new SplFileObject($tmp);
            }
        } catch (Throwable $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }

        throw new InvalidArgumentException(sprintf('The class parameter "%s" is not supported. It must be one of "SplFileInfo", "SplFileObject" or "Symfony\Component\HttpFoundation\File\File".', $type));
    }
}
