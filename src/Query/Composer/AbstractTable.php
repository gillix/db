<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\Extender\Qualifier;

abstract class AbstractTable extends AbstractMappingElement
{
    use Qualifier;

    protected ?string $alias;
    protected static self $instance;

    public function __construct(string $name, string $alias = null)
    {
        parent::__construct($name);
        $this->alias = $alias;
    }

    abstract public function join(QueryComposer $composer): array;

    public static function instance(): self
    {
        return self::$instance ?? (self::$instance =  new static());
    }

    public function alias(): ?string
    {
        return $this->alias;
    }

    public static function qualify(string $field): string
    {
        $table = self::instance();
        return $table->qualified($field, $table->alias());
    }
}
