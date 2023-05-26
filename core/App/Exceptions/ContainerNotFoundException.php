<?php

declare(strict_types=1);

namespace Core\App\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ContainerNotFoundException extends Exception implements NotFoundExceptionInterface{}