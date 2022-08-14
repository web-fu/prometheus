<?php
declare(strict_types=1);

namespace WebFu\Prometheus;

class Model implements \JsonSerializable
{
    const INT = 'int';
    const STRING = 'string';
    const FLOAT = 'float';
    const ARRAY = 'array';
    const OBJECT = 'stdClass';

    private $value;
    private $default;
    private bool $nullable;
    private string $type;

    public function __construct(array $configuration)
    {
        $this->default = $configuration['default'] ?? null;
        $this->nullable = $configuration['nullable'] ?? true;
        $this->type = $configuration['type'] ?? 'string';
        $this->set($this->default);
    }

    public function set($value): self
    {
        if (!$value && !$this->nullable) {
            //TODO error
        }

        if ($value && $this->type !== gettype($this->value)) {
            //TODO error
        }

        $this->value = $value;

        return $this;
    }

    public function get()
    {
        return $this->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'default' => $this->default,
            'nullable' => $this->nullable,
            'type' => $this->type,
        ];
    }
}