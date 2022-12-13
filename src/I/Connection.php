<?php

namespace glx\DB\I;


use glx\DB\E\ConnectionFailed;
use glx\DB\E\QueryPerformingFailed;
use glx\DB\Query\I\Query;

;

interface Connection
{
    /**
     * @return mixed
     * @throws ConnectionFailed
     */
    public function connect(): void;

    public function disconnect(): void;

    public function connected(): bool;

    /**
     * @param Query|string $query
     * @param array|null $values
     * @param null $fetch
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function query(Query|string $query, ?array $values = null, $fetch = null): mixed;

    /**
     * @param Query|string $query
     * @param array|null $values
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function execute(Query|string $query, ?array $values = null): mixed;

//    public function prepare($query);
    public function lastID();
}
