<?php
declare(strict_types=1);

namespace WebFu\Prometheus;

class Prometheus
{
    private string $rootElement;
    private array $components = [];

    public function __construct(string $rootElement = 'body')
    {
        $this->rootElement = $rootElement;
    }

    public function components(array $components = []): self
    {
        $this->components = $components;

        return $this;
    }

    public function script() : string
    {
        $scripts = array_map(function (Component $component) : string {
            return $component->render();
        }, $this->components);

        return '<script>' . implode(PHP_EOL, $scripts) . '</script>';
    }
}