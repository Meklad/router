<?php

declare(strict_types=1);

namespace Core\App;

use Psr\Container\ContainerInterface;
use Core\App\Exceptions\ContainerNotFoundException;

/**
 * This class prepare the dependancy injection container.
 */
class Container implements ContainerInterface
{
    /**
     * Array Depenancies.
     *
     * @var array
     */
    private array $entries = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get(string $id): mixed
    {
        if(!$this->has($id)) {
            throw new ContainerNotFoundException("Class " . $id . " has no bindings in  the service container.");
        }

        $entry = $this->entries[$id];

        return $entry($this);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete)
    {
        $this->entries[$id] = $concrete;
    }
}