<?php

namespace glx\DB\Query;


class Condition implements I\Condition
{
    protected string $name;
    protected string $operator;
    protected mixed $value;

    public function __construct($name, $operator = null, $value = null)
    {
        if (is_array($name)) {
            [$name, $operator, $value] = $name;
        }
        if ($value === null) {
            $value = $operator;
            $operator = is_array($value) ? 'in' : '=';
        }
        $this->name = $name;
        $this->operator = $operator;
        $this->value = $value;
    }

    public static function fetch($name, $operator = null, $value = null): I\ConditionExpression
    {
        if (is_array($name)) {
            if (is_array($name[0]) || $name[0] instanceof I\ConditionExpression) {
                $expr = seq(...$name);
            } else {
                $expr = cond($name);
            }
        } elseif ($name instanceof I\ConditionExpression) {
            $expr = $name;
        } elseif (is_string($name) && $operator === null && $value === null) {
            $expr = raw($name);
        } else {
            $expr = cond($name, $operator, $value);
        }

        return $expr;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function operator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
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
