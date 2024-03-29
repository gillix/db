<?php

namespace glx\DB\Query;

abstract class Searchable extends Builder implements I\Searchable
{

    public function where($name, $operator = null, $value = null): I\WhereClause
    {
        $expr = Condition::fetch($name, $operator, $value);
        if (!isset($this->units['where'])) {
            $this->units['where'] = $expr instanceof I\Sequence ? $expr : seq($expr);
        } else {
            $this->units['where']->add($expr);
        }

        return new WhereClause($this, $this->units['where']);
    }

    public function order(string $by, string $direction = null): I\Searchable
    {
        $this->units['order'][$by] = $direction ?? 'asc';

        return $this;
    }

    public function limit(int $count, int $offset = null): I\Searchable | I\Select
    {
        $this->units['limit'] = $count;
        if ($offset !== null) {
            $this->offset($offset);
        }

        return $this;
    }

    public function offset(int $offset): I\Searchable | I\Select
    {
        $this->units['offset'] = $offset;

        return $this;
    }
}
