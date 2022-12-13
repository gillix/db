<?php

namespace glx\DB\Query\I;


interface Searchable
{
    public function where($name, $operator = null, $value = null): WhereClause | Searchable;

    public function order(string $by, string $direction = null): Searchable;

    public function limit(int $count, int $offset = null): Searchable;

    public function offset(int $offset): Searchable;
}
