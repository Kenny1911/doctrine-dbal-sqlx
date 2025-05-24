# Doctrine SQLX

[ [English](./README.md) | Русский ]

Библиотека предоставляет удобный синтаксис для работы с параметрами в SQL-запросах Doctrine DBAL, позволяя вставлять
параметры прямо в SQL-строку.

## Установка

```bash
composer require kenny1911/doctrine-dbal-sqlx
```

## Использование

### Базовый пример

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

Вместо традиционного подхода:

```php
$result = $connection->executeQuery(
    sql: 'SELECT * FROM users WHERE id = :id',
    params: ['id' => 1],
);
```

## Преимущества

1. **Более читаемый код** - параметры видны прямо в SQL-строке
2. **Удобство рефакторинга** - не нужно синхронизировать имена параметров
3. **Безопасность** - все параметры правильно экранируются
4. **Поддержка типов** - явное указание типов параметров

## Ограничения

1. Требуется PHP 8.1+
2. Работает только с Doctrine DBAL 3.x и 4.x

## Похожие проекты

- https://github.com/thesis-php/thesis

## Лицензия

[MIT](./LICENSE)
