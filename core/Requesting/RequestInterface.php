<?php

declare(strict_types=1);

namespace Core\Requesting;

/**
 * Request Class Definition.
 */
interface RequestInterface
{
    /**
     * Factory method that bootstrap the request.
     *
     * @param array $server
     * @return self
     */
    public function bootstrapRequestComponents(array $server): self;
}