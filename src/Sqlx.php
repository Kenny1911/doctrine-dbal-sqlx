<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineSqlx;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection as DbalConnection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;

/**
 * @api
 */
final class Sqlx
{
    public function __construct(
        private readonly DbalConnection $connection,
    ) {}

    /**
     * @param callable(Ctx):string $query
     *
     * @throws Exception
     */
    public function executeQuery(callable $query, ?QueryCacheProfile $qcp = null): Result
    {
        $context = new Context();
        $sql = $query($context);

        return $this->connection->executeQuery(
            sql: $sql,
            params: $context->getParams(),
            types: $context->getTypes(),
            qcp: $qcp,
        );
    }

    /**
     * @param callable(Ctx):string $query
     *
     * @return int|numeric-string
     *
     * @throws Exception
     */
    public function executeStatement(callable $query): int|string
    {
        $context = new Context();
        $sql = $query($context);

        return $this->connection->executeStatement(
            sql: $sql,
            params: $context->getParams(),
            types: $context->getTypes(),
        );
    }
}
