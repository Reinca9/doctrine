<?php

namespace App\Framework\Renderer;

use App\Framework\Router\RouterTwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): ?TwigRenderer
    {
        $loader = new FilesystemLoader($container->get('config.view_path'));
        $twig = new Environment($loader, []);
        $extensions = $container->get('twig.extensions');

        foreach ($extensions as $extension) {
            $twig->addExtension($container->get($extension));
        }

        return new TwigRenderer($loader, $twig);
    }
}
