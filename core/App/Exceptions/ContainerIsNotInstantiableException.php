<?php

declare(strict_types=1);

namespace Core\App\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ContainerIsNotInstantiableException extends Exception implements ContainerExceptionInterface{}