<?php

namespace glx\DB;

use glx\DB\E\InvalidArgument;
use glx\DB\Query;

class Connection implements I\Connection, I\Queryable
{
    protected static string $defaultDriver = 'mysql';
    protected I\Driver $driver;

    /**
     * @throws InvalidArgument
     */
    public function __construct(array $options)
    {
        // TODO: add ability to specify connection url
        // TODO: add ability to specify write connection separately

        $driver = $options['driver'] ?? self::$defaultDriver;
        $driverClass = "\glx\DB\Drivers\\$driver\Driver";
        if (!class_exists($driverClass)) {
            throw new InvalidArgument("DB driver {$driver} is not supported");
        }
        $this->driver = new $driverClass($options);
    }

    public function connect(): void
    {
        $this->driver->connect();
    }

    public function disconnect(): void
    {
        $this->driver->disconnect();
    }

    public function connected(): bool
    {
        return $this->driver->connected();
    }

    public function query($query, ?array $values = null, $fetch = null)
    {
        return $this->driver->query($query, $values, $fetch);
    }

    public function execute($query, ?array $values = null)
    {
        return $this->driver->execute($query, $values);
    }

    public function lastID(): string
    {
        return $this->driver->lastID();
    }

    public function table($table, $alias = null): Query\I\Table
    {
        return new Query\Table($this->driver, $table, $alias);
    }

    public function from($table, $alias = null): Query\I\SearchableTable
    {
        return new Query\SearchableTable($this->driver, $table, $alias);
    }

    public function update(string $table = null, $where = null, array $fields = null): Query\I\Update
    {
        $query = new Query\Update($this->driver);
        if ($table) {
            $query->table($table);
        }
        if ($where) {
            $query->where($where);
        }
        if ($fields) {
            $query->set($fields);
        }

        return $query;
    }

    public function select(...$columns): Query\I\Select
    {
        $query = new Query\Select($this->driver);
        if ($columns) {
            $query->select(...$columns);
        }

        return $query;
    }

    public function insert(string $into = null, $fields = null): Query\I\Insert
    {
        $query = new Query\Insert($this->driver);
        if ($into) {
            $query->into($into);
        }
        if ($fields) {
            $query->set($fields);
        }

        return $query;
    }

    public function delete(string $table = null, $where = null): Query\I\Delete
    {
        $query = new Query\Delete($this->driver);
        if ($table) {
            $query->from($table);
        }
        if ($where) {
            $query->where($where);
        }

        return $query;
    }

}
