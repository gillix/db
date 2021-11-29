<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\I\Select;

class Group extends NamedExtender
{
    use Qualifier;

    protected string $field;
    protected string $table;

    public function __construct(string $field, string $table = null)
    {
        $this->field = $field;
        $this->table = $table ?? '';
    }

    public function name(): string
    {
        return $this->qualified($this->field, $this->table);
    }

    public function apply(Select $query): void
    {
        $query->group($this->qualified($this->field, $this->table));
    }


}
