<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\I\Select;

use glx\DB\Query\Not;

use function glx\DB\Query\cond;

class Filter extends NamedExtender
{
    use Qualifier;

    protected string $operator;
    protected $value;
    protected string $field;
    protected string $name;
    protected bool $include = true;
    protected string $table;
    protected bool $having = false;

    public function __construct(array $options, string $table = NULL)
    {
        $this->field = $options['column'] ?? $options[0];
        $this->value = $options['value'] ?? $options[1];
        $this->operator = $this->$options['operator'] ?? $options[2] ?: 'default';
        $this->include = (bool)($options['include'] ?? $options[3] ?? $this->include);
        $this->table = $table ?? '';
        $this->having = $options['having'] ?? $options[4] ?? $this->having;
        $this->name = $options['name'] ?? $options[5] ?? $this->field;
    }

    public function name(): string
    {
        return $this->name ?: self::qualified($this->field, $this->table);
    }

    public function apply(Select $query): void
    {
        $condition = cond(self::qualified($this->field, $this->table), $this->operator, $this->value);
        if(!$this->include) {
            $condition = new Not($condition);
        }
        if($this->having) {
            $query->having($condition);
        } else {
            $query->where($condition);
        }

    }
}
