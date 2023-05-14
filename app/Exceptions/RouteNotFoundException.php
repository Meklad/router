<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public $message = "The Requested Route Is Not Found...";

    public $code = 404;
}