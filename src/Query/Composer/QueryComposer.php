<?php

namespace glx\DB\Query\Composer;

use glx\DB\Query\Composer\I\FieldMappingInterface;
use glx\DB\Query\Composer\I\MappingElement;
use glx\DB\Query\Composer\I\QueryComposerInterface;
use glx\DB\Query\Composer\I\QueryExtender;
use glx\DB\Query\Composer\I\TableMappingInterface;
use glx\DB\Query\I\Select;

class QueryComposer implements QueryComposerInterface
{
    protected array $build;
    /** @var QueryExtender[] $extenders */
    protected array $extenders = [];
    protected array $fieldsMap;


    /**
     * @param array $options
     * @param array<string, FieldMappingInterface> $fieldsMap
     */
    public function __construct(array $options, array $fieldsMap = [])
    {
        $this->build = $options;
        $this->fieldsMap = $fieldsMap;
    }

    public function compose(Select $query): Select
    {
        foreach($this->build as $part => $items) {
            if (is_array($items) && count($items)) {
                foreach ($items as $item) {
                    foreach ($this->fetch($part, $item) as $extender) {
                        $this->extenders["$part.$extender"] = $extender;
                    }
                }
            }
        }
        while($extender = array_shift($this->extenders)) {
            $extender->apply($query);
        }

        return $query;
    }

    protected function fetch($part, $object): array
    {
        $args = [$this];
        if(is_array($object)) {
            $args = array_merge($args, $object['arguments'] ?? $object[1] ?? []);
            $object = $object['object'] ?: $object[0];
        } elseif($object instanceof QueryExtender) {
            return [$object];
        }
        if(is_string($object)) {
            $object = $this->fieldsMap[$object];
        }
        if(!$object instanceof MappingElement) {
            return [];
        }
        foreach($object->dependencies() as $depend) {
            if (!$this->extenders["{$depend[0]}.{$depend[1]}"]) {
                foreach ($this->fetch($depend[0], $depend[1]) ?: [] as $extender) {
                    $this->extenders["$depend[0].$extender"] = $extender;
                }
            }
        }
        return call_user_func([$object, $part], ...$args);
    }

    protected function add($part, $object, array $args = NULL): void
    {
        if(!$this->build["$part.$object"]) {
            $this->build["$part.$object"] = [$part, $args ? [$object, $args] : $object];
        }
    }


    public function join(TableMappingInterface $table): void
    {
        $this->add(self::JOIN, $table);
    }

    public function select(FieldMappingInterface $field): void
    {
        $this->add(self::SELECT, $field);
    }

    public function filter(FieldMappingInterface $field): void
    {
        $this->add(self::FILTER, $field);
    }

    public function order(FieldMappingInterface $field): void
    {
        $this->add(self::ORDER, $field);
    }

    public function group(FieldMappingInterface $field): void
    {
        $this->add(self::GROUP, $field);
    }

}
