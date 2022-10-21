<?php
namespace App\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

class Router
{

    /**
     *
     * @var FastRouteRouter
     */
    private $router;

    /**
     *
     * @var array
     */
    private $routes = [];


    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * Ajoute une route accessile en method GET
     *
     * @param string $path Chemin de la route
     * @param mixed $callable  Fonction ou method a appeler
     * @param string $name Nom de la route
     * @return void
     */
    public function get(string $path, $callable, string $name): void
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['GET'], $name));
        $this->routes[] = $name;
    }

    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);

        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }

        return null;
    }

    public function generateUrl(string $name, array $params = []): ?string
    {
        return $this->router->generateUri($name, $params);
    }
}
