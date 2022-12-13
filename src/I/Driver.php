<?php

namespace glx\DB\I;


use Closure;
use glx\DB\E\QueryPerformingFailed;
use glx\DB\Query\I\Query;

interface Driver extends Connection
{
    /**
     * @param Closure $execute
     * @param Query|string $query
     * @param array|null $values
     * @return mixed
     * @throws QueryPerformingFailed
     */
    public function perform(Closure $execute, Query|string $query, ?array $values = null): mixed;

    public function compiler(): QueryCompiler;
}
