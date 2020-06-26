<?php

namespace ExceptionsCenter\Laravel\API;

/**
 * Trait UrlFormatter
 * @package ExceptionsCenter\Laravel\Api
 *
 * @property string base
 * @property array  routes
 *
 * @author: Damien MOLINA
 */
trait UrlFormatter {

    /**
     * Base URL of the exceptions' center
     *
     * @var string
     */
    protected $base = "http://exceptionhost.fr" ; //TODO : to change after the server URL

    /**
     * @var array
     */
    protected $routes = [
        'send' => '/api/exception/add',
    ] ;

    /**
     * Generate the URL
     *
     * @param string $route
     * @return string
     */
    public function url(string $route) {
        if(array_key_exists($route, $this->routes)) {
            return $this->base . $this->routes[$route] ;
        }

        return "" ;
    }

}
