<?php

declare(strict_types=1);

namespace App\Common\Domain\Repository;

use App\Common\Domain\Exception\RuntimeException as DomainRuntimeException;

class RuntimeException extends DomainRuntimeException implements ExceptionInterface
{
}
