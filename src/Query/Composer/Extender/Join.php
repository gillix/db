<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\I\ConditionExpression;
use glx\DB\Query\I\Select;

class Join extends NamedExtender
{
    protected string $table;
    protected string $alias;
    protected ?ConditionExpression $on;
    protected bool $left;

    public function __construct(
        string $table,
        ConditionExpression $on = null,
        string $alias = null,
        bool $left = false
    )
    {
        $this->table = $table;
        $this->on = $on;
        $this->alias = $alias ?? '';
        $this->left = $left;
    }

    public function name(): string
    {
        return $this->table;
    }

    public function apply(Select $query): void
    {
        if ($this->on === null) {
            $query->from($this->table, $this->alias);
        } else {
            $query->join(
                $this->alias ? [$this->table, $this->alias] : $this->table,
                $this->on,
                $this->left ? 'left' : 'inner'
            );
        }
    }

    public function __toString(): string
    {
        return parent::__toString() . ($this->alias ? ".{$this->alias}" : '');
    }
}
