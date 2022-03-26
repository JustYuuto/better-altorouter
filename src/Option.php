<?php

namespace BetterAltoRouter;

class Option
{

    /**
     * @var
     */
    private $option;

    /**
     * @param $option
     * @param $router
     * @return mixed
     */
    public function getOption($option, $router)
    {
        return $router->getRouterOptions()[$option] ?? null;
    }

    /**
     * @param mixed $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

    /**
     * @param mixed $option
     * @param $router
     * @return bool
     */
    public static function isOptionDefined($option, $router): bool
    {
        return (new self)->getOption($option, $router) !== null;
    }

}