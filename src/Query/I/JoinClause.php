<?php

namespace glx\DB\Query\I;


interface JoinClause
{
    public function on($name, $operator = null, $value = null): WhereClause | Joinable;

    public function using($field): Joinable;
}
 
