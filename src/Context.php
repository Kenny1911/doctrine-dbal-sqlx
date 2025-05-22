<?php

declare(strict_types=1);

namespace Kenny1911\DoctrineDbalSqlX;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\Type;

/**
 * @api
 */
final class Context implements Ctx
{
    /** @var array<non-empty-string, mixed> */
    private array $params = [];

    /** @var array<non-empty-string, string|ParameterType|Type|ArrayParameterType> */
    private array $types = [];

    #[\Override]
    public function __invoke(
        mixed $value,
        Type|string|ParameterType|ArrayParameterType $type = ParameterType::STRING,
    ): string {
        return $this->param($value, $type);
    }

    /**
     * @return non-empty-string
     */
    public function param(
        mixed $value,
        string|ParameterType|Type|ArrayParameterType $type = ParameterType::STRING,
    ): string {
        $name = 'p' . \count($this->params);
        $this->params[$name] = $value;
        $this->types[$name] = $type;

        return ':' . $name;
    }

    /**
     * @return array<non-empty-string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array<non-empty-string, string|ParameterType|Type|ArrayParameterType>
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
