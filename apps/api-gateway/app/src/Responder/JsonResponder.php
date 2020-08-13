<?php

declare(strict_types=1);

namespace App\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonResponder implements ResponderInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function render(array $data = [], array $context = []): Response
    {
        $json = $this->serializer->serialize($data, JsonEncoder::FORMAT, $context);

        return new JsonResponse($json, 200, [], true);
    }
}
