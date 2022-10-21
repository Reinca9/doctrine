<?php

use App\Framework\Renderer\RendererInterface;
use App\Framework\Renderer\TwigRendererFactory;
use App\Framework\Router\Router;
use App\Framework\Router\RouterTwigExtension;

return [
    "config.view_path" =>  dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views',
    "twig.extensions" => [
        RouterTwigExtension::class
    ],
    RendererInterface::class => \DI\factory(TwigRendererFactory::class),
    Router::class => \DI\create()
];
