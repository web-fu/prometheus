<?php
declare(strict_types=1);

namespace WebFu\Prometheus;

class Component
{
    private string $name;
    private string $selector;
    private string $template;
    private array $methods = [];
    /** @var Model[] */
    private array $models = [];

    public function __construct(string $selector)
    {
        $this->name = 'c_' . uniqid();
        $this->selector = $selector;
    }

    public function template(string $template): self
    {
        $this->template = str_replace(PHP_EOL, '', $template);

        return $this;
    }

    public function models(array $models = []): self
    {
        $this->models = $models;

        return $this;
    }

    public function methods(array $methods = []): self
    {
        $this->methods = $methods;

        return $this;
    }

    public function render(): string
    {
        $component = $this->compile();

        return $component;
    }

    private function compileTemplate(): string
    {
        $compiled = $this->template;
        $compiled = preg_replace('/:model\s*=\s*/i', ':model=', $compiled);
        $compiled = preg_replace('/{{\s*([A-Za-z0-9_\.\[\]]+)\s*}}/', '<span :model="$1"></span>', $compiled);
        $compiled = preg_replace('/:model="([A-Za-z0-9_\.\[\]]+)"/', 'data-' . $this->name . '_$1', $compiled);

        return $compiled;
    }

    private function compileModels(): string
    {
        $fragments = [];
        foreach ($this->models as $name => $model) {
            $modelData = $model->jsonSerialize();
            $modelData['name'] = $this->name . "_" . $name;
            $fragments[] = "_('[data-" . $this->name . "_" . $name . "]').bind(" . json_encode($modelData) . ")";
        }

        return implode(PHP_EOL, $fragments);
    }

    private function compile(): string
    {
        $template = $this->compileTemplate();
        $component = json_encode([
            'name' => $this->name,
            'models' => $this->models,
        ]);

        $compiled = <<<HTML
_('$this->selector').template('$template').addComponent($component);
HTML;

        $compiled .= $this->compileModels();

        return $compiled;
    }
}