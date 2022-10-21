<?php

use App\Blog\BlogModule;
use App\Framework\App;
use App\Framework\Renderer\PHPRenderer;
use App\Framework\Renderer\TwigRenderer;
use App\Home\HomeModule;
use GuzzleHttp\Psr7\ServerRequest;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function Http\Response\send;

require dirname(__DIR__)."/vendor/autoload.php";

$modules = [
    HomeModule::class,
    BlogModule::class
];

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__). DIRECTORY_SEPARATOR .'config' . DIRECTORY_SEPARATOR . 'config.php');

foreach ($modules as $module) {
    if (!is_null($module::DEFINITIONS)) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

$container = $builder->build();

$app = new App($container, $modules);

$response = $app->run(ServerRequest::fromGlobals());

send($response);