<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

class PositionUpdateException extends RuntimeException
{
    protected $message = 'model.exception.position_update';
}
