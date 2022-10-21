<?php

namespace Tests\App;

use App\Framework\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @var Router $router
     */
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
        $this->router->get(
            "/admin/produit",
            function () {
                return "hello";
            },
            "admin.produit"
        );
    }

    public function testGet()
    {
        $request = new ServerRequest("GET", "/admin/produit");
        $route = $this->router->match($request);
        $this->assertEquals("admin.produit", $route->getName());
        $this->assertEquals("hello", call_user_func_array($route->getCallback(), [$request]));
    }

    public function testGenerateUrl()
    {
        $url = $this->router->generateUrl('admin.produit');

        $this->assertEquals("/admin/produit", $url);
    }
}
