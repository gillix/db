<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\Composer\I\QueryExtender;

abstract class NamedExtender implements QueryExtender
{
    abstract public function name(): string;

    public function __toString(): string
    {
        return $this->name();
    }
}
