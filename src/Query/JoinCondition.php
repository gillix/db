<?php

namespace glx\DB\Query;


class JoinCondition implements I\JoinCondition
{
    protected array $condition;

    public function init($condition, string $type = 'on'): void
    {
        $this->condition = ['type' => $type, 'condition' => $condition];
    }

    public function initialized(): bool
    {
        return isset($this->condition);
    }

    public function condition(): array
    {
        return $this->condition ?? [];
    }
}
