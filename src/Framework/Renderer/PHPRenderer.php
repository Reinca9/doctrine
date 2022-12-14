<?php
namespace App\Framework\Renderer;

class PHPRenderer implements RendererInterface
{

    const DEFAULT_NAMESPACE = "__MAIN";

    private array $paths = [];

    private array $globals = [];

    public function __construct(?string $defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }

    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    public function addPath(string $namespace, ?string $path = null): void
    {
        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }

    //$renderer->render("@home/index")
    public function render(string $view, array $params = []): string
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }

    private function hasNamespace(string $view): bool
    {
        return $view[0] === "@";
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }


    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        $str = str_replace('@'. $namespace, $this->paths[$namespace], $view);
        return str_replace('/', '\\', $str);
    }
}
