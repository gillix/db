<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\I\Select;

class Field extends NamedExtender
{
    use Qualifier;

    protected string $field;
    protected string $table;
    protected string $alias;

    public function __construct(string $field, string $table = null, string $alias = null)
    {
        $this->field = $field;
        $this->table = $table;
        $this->alias = $alias;
    }

    public function name(): string
    {
        return $this->alias ?: $this->qualified($this->field, $this->table);
    }

    public function apply(Select $query): void
    {
        $query->select($this->aliased());
    }

    protected function aliased(): string
    {
        if ($this->alias) {
            return "{$this->qualified($this->field, $this->table)} AS {$this->alias}";
        }
        return $this->qualified($this->field, $this->table);
    }
}
