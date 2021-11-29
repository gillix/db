<?php

namespace glx\DB\Query\Composer;

abstract class AbstractTable extends AbstractMappingElement
{
    protected ?string $alias;

    public function __construct(string $name, string $alias = null)
    {
        parent::__construct($name);
        $this->alias = $alias;
    }

    abstract public function join(QueryComposer $composer): void;
}
