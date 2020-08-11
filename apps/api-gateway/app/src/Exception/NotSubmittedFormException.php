<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class NotSubmittedFormException extends Exception
{
    protected $message = 'exception.not_submitted_form';
}
