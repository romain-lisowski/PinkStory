<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Http;

use App\Common\Domain\Security\SecurityInterface;
use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Infrastructure\Messenger\MessageInterface;
use ReflectionClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class MessageParamConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;
    private SecurityInterface $security;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, SecurityInterface $security, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->security = $security;
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver, $configuration->getOptions());
            $options = $resolver->resolve($configuration->getOptions());

            $content = json_decode($request->getContent(), true);

            foreach ($options['mapping'] as $field => $value) {
                if (true === str_contains($value, 'request.attribute.')) {
                    $content[$field] = $request->attributes->get(substr($value, strlen('request.attribute.')));
                }

                if ('security.user.id' === $value) {
                    $content[$field] = $this->security->getUser()->getId();
                }

                $enumMatches = [];
                if (1 === preg_match('/^enum\.([\\\\\w+]+)::(\w+)$/', $value, $enumMatches)) {
                    $class = new ReflectionClass($enumMatches[1]);
                    $content[$field] = $class->getConstant($enumMatches[2]);
                }
            }

            $command = $this->serializer->deserialize(json_encode($content), $configuration->getClass(), JsonEncoder::FORMAT);

            $this->validator->validate($command);

            $request->attributes->set($configuration->getName(), $command);
        } catch (ExceptionInterface | ValidationFailedException $e) {
            throw new MessageConversionFailedException($e);
        }
    }

    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        $class = new ReflectionClass($configuration->getClass());

        if (false === $class->implementsInterface(MessageInterface::class)) {
            return false;
        }

        return true;
    }

    private function configureOptions(OptionsResolver $resolver, array $options): void
    {
        $resolver->setDefault('mapping', function (OptionsResolver $mappingResolver) use ($options) {
            if (true === array_key_exists('mapping', $options)) {
                foreach (array_keys($options['mapping']) as $field) {
                    $mappingResolver->setDefault($field, '');
                    $mappingResolver->setAllowedTypes($field, 'string');
                    $mappingResolver->setAllowedValues($field, function ($value) use ($field) {
                        if ('request.attribute.'.$field === $value) {
                            return true;
                        }

                        if ('security.user.id' === $value) {
                            return true;
                        }

                        $enumMatches = [];
                        if (1 === preg_match('/^enum\.([\\\\\w+]+)::(\w+)$/', $value, $enumMatches)) {
                            $class = new ReflectionClass($enumMatches[1]);

                            return $class->hasConstant($enumMatches[2]);
                        }

                        return false;
                    });
                }
            }
        });
    }
}
