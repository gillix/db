<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\Composer\I\QueryExtender;
use glx\DB\Query\I\Select;

class ClosureExtender implements QueryExtender
{
    protected \Closure $func;

    public function __construct(\Closure $func, object $caller = null)
    {
        $this->func = $func;
        $this->func->bindTo($caller ?? $this);
    }

    public function apply(Select $query): void
    {
        ($this->func)($query);
    }
}
