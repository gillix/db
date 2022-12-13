<?php

namespace glx\DB;

use Closure;
use glx\DB;
use glx\DB\E\ConnectionFailed;
use glx\DB\E\DBException;
use glx\DB\E\QueryPerformingFailed;
use glx\DB\Query\I\Query;
use PDO;
use PDOException;
use PDOStatement;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class pdoDriver implements I\Driver
{
    protected static array $fetchModes = [
        'object' => PDO::FETCH_OBJ,
        'array' => PDO::FETCH_ASSOC,
        'class' => PDO::FETCH_CLASS,
        'column' => PDO::FETCH_COLUMN,
    ];
    protected static array $attributes = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    protected PDO $pdo;
    protected array $options;
    protected int $fetchMode = PDO::FETCH_ASSOC;
    protected LoggerInterface $logger;

    public function __construct(array $options, LoggerInterface $logger = null)
    {
        $this->options = $options;
        if ($options['fetch']) {
            $this->fetchMode = self::$fetchModes[$options['fetch']] ?? $this->fetchMode;
        }
        $this->logger = $logger ?? new NullLogger();
    }

    public function disconnect(): void
    {
        if ($this->connected()) {
            unset($this->pdo);
        }
    }

    public function connected(): bool
    {
        return isset($this->pdo);
    }

    /**
     * @param Query|string $query
     * @param array|null $values
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function execute(Query|string $query, ?array $values = null): mixed
    {
        return $this->perform(function ($query, $values) {
            $stmt = $this->prepare($query);

            if ($values) {
                static::bind($stmt, $values);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }, $query, $values);
    }

    /**
     * using common method for raising event and other common things
     * @param Closure $execute
     * @param Query | string $query
     * @param null | array $values
     * @return mixed
     * @throws E\ConnectionFailed|E\QueryPerformingFailed
     */
    public function perform(Closure $execute, Query|string $query, ?array $values = null): mixed
    {
        $this->connect();
        if ($query instanceof Query) {
            [$query, $values] = $query->compile();
        }
        try {
            $result = $execute($query, $values);
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'query' => $query,
                'values' => $values,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ]);
            throw new QueryPerformingFailed($query, $values, $e->getMessage(), 0, $e);
        }
        $this->logger->debug((string)$query, compact('values'));

        return $result;
    }

    /**
     * @param false $force
     * @throws E\ConnectionFailed
     */
    public function connect(bool $force = false): void
    {
        if ($force || !$this->connected()) {
            try {
                $this->pdo = new PDO(
                    static::makeURL($this->options),
                    $this->options['username'],
                    $this->options['password'],
                    static::attributes($this->options) + static::$attributes
                );
            } catch (PDOException $e) {
                // TODO: detect if lost connection and reconnect
                throw new E\ConnectionFailed('DB connection failed', 0, $e);
            }
        }
    }

    abstract protected static function makeURL(array $options): string;

    abstract protected static function attributes(array $options): array;

    /**
     * @param $query
     * @return PDOStatement
     * @throws E\ConnectionFailed
     */
    public function prepare($query): PDOStatement
    {
        $this->connect();

        return $this->pdo->prepare($query);
    }

    public static function bind(PDOStatement $stmt, array $values): void
    {
        foreach ($values as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }

    /**
     * @param Query|string $query
     * @param array|null $values
     * @param null $fetch
     * @return mixed
     * @throws ConnectionFailed
     * @throws QueryPerformingFailed
     */
    public function query(Query|string $query, ?array $values = null, $fetch = null): mixed
    {
        return $this->perform(function ($query, $values) use ($fetch) {
            $stmt = $this->prepare($query);

            if ($values) {
                static::bind($stmt, $values);
            }

            $stmt->execute();

            $fetch = (array)$fetch;
            $fetch[0] ??= $this->fetchMode;

            return $stmt->fetchAll(...$fetch);
        }, $query, $values);
    }

    /**
     * @return string
     * @throws DBException
     */
    public function lastID(): string
    {
        if ($this->connected()) {
            return $this->pdo->lastInsertId();
        }
        throw new DBException('Can`t fetch last inserted ID if not connected to DB');
    }
}
