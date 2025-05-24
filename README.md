# Doctrine SQLX

[ English | [Русский](./README-RU.md) ]

A library that provides a convenient syntax for working with parameters in Doctrine DBAL SQL queries, allowing direct
parameter embedding in SQL strings.

## Installation

```bash
composer require kenny1911/doctrine-dbal-sqlx
```

## Usage

### Basic Example

```php
use Doctrine\DBAL\Connection;
use Kenny1911\DoctrineSqlx\Sqlx;
use Kenny1911\DoctrineSqlx\Ctx;

/** @var Connection $connection */
$sqlx = new Sqlx($connection);

$result = $connection->executeQuery(static fn(Ctx $ctx): string => <<<SQL
    "SELECT * FROM users WHERE id = {$ctx(1)}"
    SQL);
);
```

Instead of the traditional approach:

```php
$result = $connection->executeQuery(
    sql: 'SELECT * FROM users WHERE id = :id',
    params: ['id' => 1],
);
```

## Benefits

1. **More readable code** - parameters are visible directly in SQL strings
2. **Easier refactoring** - no need to synchronize parameter names
3. **Security** - all parameters are properly escaped
4. **Type support** - explicit parameter type specification

## Limitations

1. Requires PHP 8.1+
2. Works only with Doctrine DBAL 3.x and 4.x

## Related Projects

- https://github.com/thesis-php/thesis

## License

[MIT](./LICENSE)
