<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineDbalSqlX;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\Type;

/**
 * @api
 */
interface Ctx
{
    /**
     * @return non-empty-string
     */
    public function __invoke(
        mixed $value,
        string|ParameterType|Type|ArrayParameterType $type = ParameterType::STRING,
    ): string;
}
