<?php
namespace App\Framework\Router;


/**
 * Représente une route qui à été matché par le router
 */
class Route
{

    /**
     *
     * @var string
     */
    private string $name;

    /**
     *
     * @var string|callable
     */
    private $callable;

    /**
     *
     * @var array
     */
    private array $params;

    public function __construct(string $name, $callable, array $params)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->params = $params;
    }

    /**
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     *
     * @return string|callable
     */
    public function getCallback()
    {
        return $this->callable;
    }

    /**
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

}