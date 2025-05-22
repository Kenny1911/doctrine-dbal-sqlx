<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineDbalSqlX\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Kenny1911\DoctrineDbalSqlX\Connection;
use Kenny1911\DoctrineDbalSqlX\Ctx;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-internal Kenny1911\DoctrineDbalSqlX\Tests
 */
final class ConnectionTest extends TestCase
{
    private Connection $connection;

    /**
     * @throws Exception
     */
    #[\Override]
    protected function setUp(): void
    {
        $dbalConnection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);

        $schema = new Schema();
        $table = $schema->createTable('users');
        $table->addColumn('id', Types::INTEGER)->setNotnull(true);
        $table->addColumn('username', Types::STRING)->setNotnull(true);
        $table->addColumn('password', Types::STRING)->setNotnull(true);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username']);

        $dbalConnection->createSchemaManager()->createSchemaObjects($schema);

        $dbalConnection->executeStatement(
            <<<'SQL'
                INSERT INTO users
                VALUES
                    (1, 'foo', '123'),
                    (2, 'bar', '456')
                SQL,
        );

        $this->connection = new Connection($dbalConnection);
    }

    /**
     * @throws Exception
     */
    public function testExecuteQuery(): void
    {
        $result = $this->connection->executeQuery(static fn(Ctx $ctx): string => <<<SQL
            SELECT * FROM users WHERE username = {$ctx('foo')}
            SQL);

        $data = $result->fetchAllAssociative();

        self::assertSame(
            expected: [
                ['id' => 1, 'username' => 'foo', 'password' => '123'],
            ],
            actual: $data,
        );
    }

    /**
     * @throws Exception
     */
    public function testExecuteStatement(): void
    {
        $affectedRows = $this->connection->executeStatement(static fn(Ctx $ctx): string => <<<SQL
            UPDATE users SET password = {$ctx('321')} WHERE id = {$ctx(1)}
            SQL);
        $updatedPassword = $this->connection->executeQuery(static fn(Ctx $ctx): string => <<<SQL
            SELECT password FROM users WHERE id = {$ctx(1)}
            SQL)->fetchOne();

        self::assertSame(1, $affectedRows);
        self::assertSame('321', $updatedPassword);
    }
}
