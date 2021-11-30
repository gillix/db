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

    abstract public function join(QueryComposer $composer): array;

    public function alias(): ?string
    {
        return $this->alias;
    }

    public static function qualify(string $field): string
    {
        $table = self::instance();
        return self::qualified($field, $table->alias());
    }

    public static function field(string $field): string
    {
        return self::qualify($field);
    }
}
