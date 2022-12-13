<?php

namespace glx\DB\Query\I;


interface JoinCondition
{
    public function init($condition, string $type = 'on'): void;

    public function initialized(): bool;

    public function condition(): array;
}
