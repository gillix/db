<?php

namespace glx\DB\Query\I;


interface JoinableTable extends Joinable, Fetching
{
    /** updates table with specified values and returns count of affected rows
     * @param array | string $name
     * @param mixed | null $value
     * @return int
     */
    public function update(array|string $name, mixed $value = null): int;

    public function select(...$columns): Select;
}
