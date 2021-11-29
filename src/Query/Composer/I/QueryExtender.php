<?php

namespace glx\DB\Query\Composer\I;

use glx\DB\Query\I\Select;

interface QueryExtender
{
    public function apply(Select $query): void;
}
