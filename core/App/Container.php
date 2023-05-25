<?php

declare(strict_types=1);

namespace Core\App;

use App\Controllers\Controller;
use App\Controllers\HomeController;
use ReflectionClass;
use ReflectionParameter;
use Psr\Container\ContainerInterface;
use Core\App\Exceptions\ContainerNotFoundException;
use Core\App\Exceptions\ContainerIsNotInstantiableException;
use ReflectionNamedType;
use ReflectionUnionType;

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
        if($this->has($id)) {
            $entry = $this->entries[$id];
            return $entry($this);
        } else {
            return $this->resolve($id);
        }


        throw new ContainerNotFoundException("Class " . $id . " has no bindings in  the service container.");
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

    /**
     * Set entry in service container using entry id.
     *
     * @param string $id
     * @param callable $concrete
     * @return void
     */
    public function set(string $id, callable $concrete)
    {
        $this->entries[$id] = $concrete;
    }

    /**
     * In case if has() method returns false
     * the resolve method will use auto-wiring
     * to resolve and get the entry back using
     * the reflection class to determine the entry
     * with its dependencies.
     *
     * @param string $id
     * @throws ContainerIsNotInstantiableException
     * 
     * @return ReflectionClass
     */
    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);

        if(!$reflectionClass->isInstantiable()) {
            throw new ContainerIsNotInstantiableException("Class {$id} Is Not Instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();

        if(!$constructor || !$parameters) {
            return $reflectionClass->newInstance();
        }

        $dependencies = $this->resolveDependencies($parameters, $id);

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    /**
     * Try to resolve the dependencies and return it as array.
     *
     * @param array $parameters
     * @param string $id
     * @throws ContainerIsNotInstantiableException
     * 
     * @return array
     */
    private function resolveDependencies(array $parameters, string $id): array
    {
        return array_map(function(ReflectionParameter $parameter) use($id) {
            $paramName = $parameter->getName();
            $paramType = $parameter->getType();
            
            if(!$paramType) {
                throw new ContainerIsNotInstantiableException("Failed to resolve class {$id} because parameter {$paramName} is missing a type hint.");
            }

            if($paramType instanceof ReflectionUnionType) {
                throw new ContainerIsNotInstantiableException("Failed to resolve class {$id} because of union type for parameter {$paramName}");
            }

            if($paramType instanceof ReflectionNamedType && !$paramType->isBuiltin()) {
                return $this->get($paramType->getName());
            }

            throw new ContainerIsNotInstantiableException("Failed to resolve class {$id} because invalid parameter {$paramName}");
        }, $parameters);
    }
}