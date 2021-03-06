<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\I\MappingElement;

abstract class AbstractMappingElement implements MappingElement
{
    protected string $name;
    protected array $dependencies;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->dependencies = [];
    }

    public function depends(string $part, MappingElement $element): void
    {
        $this->dependencies[] = [$part, $element];
    }

    public function dependencies(): array
    {
        return $this->dependencies;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
