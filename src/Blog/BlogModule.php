<?php

namespace App\Blog;

use App\Framework\Renderer\RendererInterface;
use App\Framework\Router\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule extends \App\Framework\Module
{
    public const DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

    private Router $router;
    private RendererInterface $renderer;

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->renderer->addPath('blog', __DIR__ . DIRECTORY_SEPARATOR . 'views');
        $this->router->get("/blog", [$this, 'index'], 'blog.index');
        $this->router->get("/blog/show/{slug:[a-zA-Z\-]+}-{id:\d+}", [$this, 'show'], 'blog.show');
    }


    public function index(ServerRequestInterface $request)
    {
        return $this->renderer->render('@blog/index');
    }

    public function show(ServerRequestInterface $request)
    {
        $params = $request->getAttributes();
        $id = $params['id'];
        $slug = $params['slug'];
        return $this->renderer->render('@blog/show', [
            "id" => $id,
            "slug" => $slug
        ]);
    }
}
