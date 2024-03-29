<?php

namespace glx\DB\Query;


class Raw implements I\ConditionExpression
{
    protected string $raw;
    protected array $values;

    public function __construct(string $raw, ...$values)
    {
        $this->raw = $raw;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }

    public function or($name, $operator = null, $value = null): I\Sequence
    {
        return _or_($this, cond($name, $operator, $value));
    }

    public function and($name, $operator = null, $value = null): I\Sequence
    {
        return _and_($this, cond($name, $operator, $value));
    }
}
