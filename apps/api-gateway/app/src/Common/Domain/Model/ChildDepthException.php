<?php

declare(strict_types=1);

namespace App\Common\Domain\Model;

class ChildDepthException extends RuntimeException
{
    protected $message = 'model.exception.child_depth';
}
