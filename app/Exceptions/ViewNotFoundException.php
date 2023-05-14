<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    public $message = "The Requested View Page Is Not Found...";

    public $code = 404;
}