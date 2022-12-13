<?php

namespace glx\DB\Query;


class JoinableTable extends Joinable implements I\JoinableTable
{

    public function get($callback = null): I\Result
    {
        return Select::createFrom($this)->get($callback);
    }

    public function one(): I\Result
    {
        return Select::createFrom($this)->one();
    }

    public function page($page, $pp = null): I\Paginated
    {
        return Select::createFrom($this)->page($page, $pp);
    }

    public function column($column = null): I\Result
    {
        return Select::createFrom($this)->column($column);
    }

    public function object(string $class = null, array $args = null): mixed
    {
        return Select::createFrom($this)->object($class, $args);
    }

    public function update(array|string $name, mixed $value = null): int
    {
        return Update::createFrom($this)->set($name, $value)->perform();
    }

    public function select(...$columns): I\Select
    {
        return Select::createFrom($this)->select(...$columns);
    }

    public function group(...$columns): I\Select
    {
        return Select::createFrom($this)->group(...$columns);
    }

    public function value($column = null)
    {
        return Select::createFrom($this)->value($column);
    }

    public function aggregated(array $columns, $page, $pp = null): I\Aggregated
    {
        return Select::createFrom($this)->aggregated($columns, $page, $pp);
    }
}
