<?php

declare(strict_types=1);

namespace App;

use Exception;
use ReflectionClass;
use ReflectionException;

class Container
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function make(string $class)
    {
        $reflector = new ReflectionClass($class);

        if (!$constructor = $reflector->getConstructor()) {
            return new $class;
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();

            if ($type === null) {
                throw new Exception("Cannot resolve dependency {$param->name}");
            }

            $dependencies[] = $this->make($type->getName());
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}