<?php

namespace glx\DB\Query\Composer\I;

interface TableMappingInterface extends MappingElement
{
    public function alias(): ?string;

    public function join();

    public static function instance(): self;

    public static function field(string $field): string;
}
