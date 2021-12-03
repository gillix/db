<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\Extender\Qualifier;
use glx\DB\Query\Composer\I\TableMappingInterface;

abstract class AbstractTable extends AbstractMappingElement implements TableMappingInterface
{
    use Qualifier;

    protected ?string $alias;

    public function __construct(string $name, string $alias = null)
    {
        parent::__construct($name);
        $this->alias = $alias;
    }

    public static function instance(): self
    {
        throw new \RuntimeException("Abstract class cannot be instantiated, use trait Singleton in the table mapper class");
    }

    abstract public function join(): array;

    public function alias(): ?string
    {
        return $this->alias;
    }

    public static function qualify(string $field): string
    {
        $table = static::instance();
        return self::qualified($field, $table->alias() ?? $table->name());
    }

    public static function field(string $field): string
    {
        return self::qualify($field);
    }
}
