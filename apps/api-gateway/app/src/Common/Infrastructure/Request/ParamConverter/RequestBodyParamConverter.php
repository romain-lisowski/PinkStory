<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Request\ParamConverter;

use App\Common\Domain\Validator\ValidationFailedException;
use App\Common\Domain\Validator\ValidatorInterface;
use App\User\Infrastructure\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;

final class RequestBodyParamConverter implements ParamConverterInterface
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

    public function apply(Request $request, ParamConverter $configuration): bool
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
                    // need to be authenticated
                    if (null === $this->security->getUser()) {
                        throw new AccessDeniedException();
                    }

                    $content[$field] = $this->security->getUser()->getId();
                }

                $enumMatches = [];
                if (1 === preg_match('/^enum\.([\\\\\w+]+)::(\w+)$/', $value, $enumMatches)) {
                    $class = new \ReflectionClass($enumMatches[1]);
                    $content[$field] = $class->getConstant($enumMatches[2]);
                }
            }

            $object = $this->serializer->deserialize(json_encode($content), $configuration->getClass(), JsonEncoder::FORMAT);

            $this->validator->validate($object);

            $request->attributes->set($configuration->getName(), $object);

            return true;
        } catch (MissingConstructorArgumentsException $e) {
            throw new RequestBodyParamMissingMandatoryException($e);
        } catch (ValidationFailedException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new RequestBodyParamConversionFailedException($e);
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && 'request_body' === $configuration->getConverter();
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
                            $class = new \ReflectionClass($enumMatches[1]);

                            return $class->hasConstant($enumMatches[2]);
                        }

                        return false;
                    });
                }
            }
        });
    }
}
