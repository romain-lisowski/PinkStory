<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonResponder extends AbstractResponder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function renderResponse(array $data = [], array $options = []): Response
    {
        // add default group for serialization
        $options['context']['groups'][] = 'serializer';

        $json = $this->serializer->serialize($data, JsonEncoder::FORMAT, $options['context']);

        return new JsonResponse($json, 200, [], true);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('context', function (OptionsResolver $contextResolver) {
            $contextResolver->setDefault('groups', []);
            $contextResolver->setAllowedTypes('groups', 'array');
        });
    }
}
