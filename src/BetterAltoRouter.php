<?php

namespace BetterAltoRouter;

use BetterAltoRouter\Exception\FileNotFoundException;
use \AltoRouter;
use Exception;

class BetterAltoRouter
{

    /**
     * @var array
     */
    private $options;

    /**
     * @var AltoRouter
     */
    private $router;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->router = new \AltoRouter();
    }

    /**
     * @param string $url The route URL
     * @param string $file The file to load when the route is
     * @param string|null $name The route name, can be used with {@see BetterAltoRouter::generate()}
     * @return BetterAltoRouter
     */
    public function get(string $url, string $file, string $name = null): BetterAltoRouter
    {
        $this->match('GET', $url, $file, $name);

        return $this;
    }

    /**
     * @param string|array $method The allowed method(s) to access the route. It can be:<br/>
     *                             &bull; A string if you have ONE method<br/>
     *                             &bull; An array, with one method per item, if you have 2+ methods
     * @param string $url The route URL
     * @param string $file The file to load when the route is
     * @param string|null $name The route name, can be used with {@see BetterAltoRouter::generate()}
     * @return void
     */
    public function match($method, string $url, string $file, string $name = null)
    {
        $methods = '';
        if (gettype($method) === 'array') {
            foreach ($method as $_method) {
                $methods = join('|', $_method);
            }
        } else {
            $methods = $method;
        }

        return $this->router->map($methods, $url, $file, $name);
    }

    /**
     * **You NEED to run** this function, otherwise the router **will NOT work**!
     * @return BetterAltoRouter
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function run(): BetterAltoRouter
    {
        $match = $this->router->match();
        $file = $match['target'];

        ob_start();

        if (!Option::isOptionDefined('files_path', $this)) throw new Exception("The option \"files_path\" is not defined");
        if (!Option::isOptionDefined('layout_file', $this)) throw new Exception("The option \"layout_file\" is not defined");

        if (
            !file_exists(realpath($this->options['files_path'] . DIRECTORY_SEPARATOR . $file . '.php')) ||
            !file_exists($this->options['files_path'] . DIRECTORY_SEPARATOR . $this->options['layout_file'])
        ) {
            throw new FileNotFoundException(realpath($this->options['files_path'] . DIRECTORY_SEPARATOR . $file . '.php'));
        }
        $file = $this->options['files_path'] . DIRECTORY_SEPARATOR . $file . '.php';
        require realpath($file);

        $content = ob_get_clean();
        require $this->options['files_path'] . DIRECTORY_SEPARATOR . $this->options['layout_file'];

        return $this;
    }

    public function getRouterOptions(): array
    {
        return $this->options;
    }

}