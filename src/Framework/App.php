<?php
namespace App\Framework;

use App\Framework\Renderer\PHPRenderer;
use App\Framework\Renderer\RendererInterface;
use App\Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    /**
     * Contient l'instance du router de l'application
     *
     * @var Router
     */
    private Router $router;

    /**
     * Contient les modules de l'application
     *
     * @var array
     */
    private array $modules;

    /**
     * contient le Renderer de l'application
     *
     */
    private $renderer;

    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->router = $container->get(Router::class);
        $this->renderer = $container->get(RendererInterface::class);
        foreach ($modules as $module) {
            $this->modules[] = $container->get($module);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {

        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === '/' && $uri != '/') {
            return (new Response())
                    ->withStatus(301)
                    ->withHeader('Location', substr($uri, 0, -1));
        }

        $route = $this->router->match($request);

        if (is_null($route)) {
            return new Response(404, [], "<h1>Cette page n'existe pas.</h1>");
        }

        $params = $route->getParams();

        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $callback = $route->getCallback();

        $response = call_user_func_array($callback, [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception("Le serveur n'a pas renvoyer de reponse valide.");
        }
    }
}
