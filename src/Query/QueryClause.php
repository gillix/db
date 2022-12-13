<?php

namespace glx\DB\Query;


class QueryClause implements I\QueryClause
{
    protected mixed $target;

    public function __construct(mixed $target)
    {
        $this->target = $target;
    }

    public function target(): mixed
    {
        return $this->target;
    }
}
