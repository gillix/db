<?php

namespace glx\DB\Query\I;


interface Joinable extends Searchable
{
    public function join(string|array|Query $table, $on = null, string $type = 'inner'): JoinClause | self;

    public function left(string|array|Query $table, array|Condition $on = null): JoinClause | self;

    public function right(string|array|Query $table, array|Condition $on = null): JoinClause | self;

    public function cross(string|array|Query $table, array|Condition $on = null): JoinClause | self;
}
