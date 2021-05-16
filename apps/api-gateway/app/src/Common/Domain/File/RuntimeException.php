<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

use App\Common\Domain\Exception\RuntimeException as DomainRuntimeException;

class RuntimeException extends DomainRuntimeException implements ExceptionInterface
{
}
