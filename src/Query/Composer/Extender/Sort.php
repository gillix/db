<?php

namespace glx\DB\Query\Composer\Extender;

use glx\DB\Query\I\Select;

class Sort extends NamedExtender
{
    use Qualifier;

    public const DIRECTION_ASC = 'ASC';
    public const DIRECTION_DESC = 'DESC';

    protected string $table;
    protected string $by;
    protected string $direction;


    public function __construct(
        string $by,
        string $direction = self::DIRECTION_ASC,
        string $table = null
    )
    {
        $this->by = $by;
        $this->direction = $direction;
        $this->table = $table ?? '';
    }

    public function name(): string
    {
        return $this->qualified($this->by, $this->table);
    }

    public function apply(Select $query): void
    {
        $query->order($this->qualified($this->by, $this->table), $this->direction);
    }
}
