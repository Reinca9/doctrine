<?php

use App\Home\HomeModule;

return [
    "home.prefix" => "/home",
    HomeModule::class => \DI\autowire()->constructorParameter("prefix", \DI\get('home.prefix'))
];
