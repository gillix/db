<?php

namespace glx\DB\Query\Composer\I;

interface FieldMappingInterface extends MappingElement
{
    public function select(): array;
    public function filter($value, string $operator, bool $include = true): array;
    public function order(string $direction): array;
    public function group(): array;
}
