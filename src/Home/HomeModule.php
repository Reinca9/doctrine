<?php
namespace App\Home;

use App\Framework\Module;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;

class HomeModule extends Module
{

    public const DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

    private $renderer;

    private Router $router;

    public function __construct(string $prefix, Router $router, RendererInterface $renderer)
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->renderer->addPath('home', __DIR__. DIRECTORY_SEPARATOR . 'view');
        $this->router->get(
            $prefix,
            [$this, 'index'],
            "home.index"
        );

        $this->router->get(
            $prefix."/hello/{name:[a-zA-Z]+}",
            [$this, 'hello'],
            'home.hello'
        );
    }

    public function index(ServerRequest $request): string
    {
        $this->renderer->addGlobal("router", $this->router);
        return $this->renderer->render("@home/index");
    }

    public function hello(ServerRequest $request): string
    {
        $name = $request->getAttribute('name');
        return $this->renderer->render("@home/test", [
            "nom" => $name
        ]);
    }
}
