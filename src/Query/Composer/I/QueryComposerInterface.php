<?php

namespace glx\DB\Query\Composer\I;

use glx\DB\Query\I\Select;

interface QueryComposerInterface
{
    public const SELECT = 'select';
    public const FILTER = 'filter';
    public const JOIN = 'join';
    public const ORDER = 'order';
    public const GROUP = 'group';

    public function query(Select $query = null): Select;

    public function join(TableMappingInterface $table): void;
    public function select(FieldMappingInterface $field): void;
    public function filter(FieldMappingInterface $field): void;
    public function order(FieldMappingInterface $field): void;
    public function group(FieldMappingInterface $field): void;
}
