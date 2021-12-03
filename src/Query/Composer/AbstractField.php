<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\Extender\Field;
use glx\DB\Query\Composer\Extender\Filter;
use glx\DB\Query\Composer\Extender\Group;
use glx\DB\Query\Composer\Extender\Sort;
use glx\DB\Query\Composer\I\FieldMappingInterface;
use glx\DB\Query\Composer\I\QueryComposerInterface;
use glx\DB\Query\Composer\I\TableMappingInterface;

abstract class AbstractField extends AbstractMappingElement implements FieldMappingInterface
{
    protected TableMappingInterface $table;
    protected ?string $sourceField;

    public function __construct(string $name, TableMappingInterface $table, string $sourceField = null)
    {
        parent::__construct($name);
        $this->table = $table;
        $this->sourceField = $sourceField ?? $name;
        $this->depends(QueryComposerInterface::JOIN, $table);
    }

    public function table(): TableMappingInterface
    {
        return $this->table;
    }

    public function select(): array
    {
        return [new Field($this->sourceField, $this->table()->alias(), $this->name())];
    }

    public function filter($value, string $operator, bool $include = true): array
    {
        return [new Filter([
            'column' => $this->sourceField,
            'name' => $this->name(),
            'value' => $value,
            'operator' => $operator,
            'include' => $include
        ], $this->table()->alias())];
    }

    public function order(string $direction): array
    {
        return [new Sort($this->sourceField, $direction)];
    }

    public function group(): array
    {
        return [new Group($this->sourceField, $this->table()->alias())];
    }
}
