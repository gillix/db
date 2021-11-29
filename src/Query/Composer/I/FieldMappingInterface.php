<?php

namespace glx\DB\Query\Composer\I;

use glx\DB\Query\Composer\QueryComposer;

interface FieldMappingInterface extends MappingElement
{
    public function select(QueryComposer $composer): array;
    public function filter(QueryComposer $composer, $value, string $operator, bool $include = true): array;
    public function order(QueryComposer $composer, string $direction): array;
    public function group(QueryComposer $composer): array;
}
