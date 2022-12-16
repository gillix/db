<?php

namespace glx\DB\Query\I;


use glx\DB\E\ConnectionFailed;
use glx\DB\E\QueryPerformingFailed;

interface Fetching
{
    /**
     * @param null $callback
     * @return Result
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function get($callback = null): Result;

    /**
     * @return Result
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function one(): Result;

    /**
     * @param null $column
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function value($column = null): mixed;

    /**
     * @param array $columns
     * @param int $page
     * @param int|null $pp
     * @return Aggregated
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function aggregated(array $columns, int $page, int $pp = null): Aggregated;

    /**
     * @param int $page
     * @param int|null $pp
     * @return Paginated
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function page(int $page, int $pp = null): Paginated;

    /**
     * @param string|int|null $column
     * @return Result
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function column(string|int $column = null): Result;

    /**
     * @param class-string | null $class
     * @param array|null $args
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function object(string $class = null, array $args = null): mixed;
    // TODO: + key pair
}
