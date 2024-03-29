<?php

namespace glx\DB\Query\I;

interface Select extends Joinable, Query, Fetching
{
    public function select(...$columns): self;

    public function having($name, $operator, $value): WhereClause | self;

    public function group(...$columns): self;

    public function from(string|Query $table, string $alias = null): self;
}

