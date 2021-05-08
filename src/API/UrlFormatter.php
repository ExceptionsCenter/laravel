<?php

namespace ExceptionsCenter\Laravel\API;

/**
 * Trait UrlFormatter
 * @package ExceptionsCenter\Laravel\Api
 *
 * @property array  routes
 * @property string base
 *
 * @author: Damien MOLINA
 */
trait UrlFormatter {

    /**
     * @var array
     */
    protected $routes = [
        'send' => '/api/exception/add',
    ] ;

    /**
     * The URL base of the destination
     * server. You can send your request
     * in the server you want.
     */
    protected $base ;

    /**
     * Generate the URL
     *
     * @param string $route
     * @return string|null
     */
    public function url(string $route) {
        if(array_key_exists($route, $this->routes)) {
            $base = $this->getBaseURL() ;

            if($base !== null) {
                if(str_ends_with($base, '/')) {
                    $base = substr($base, 0, strlen($base) - 1);
                }

                return $base . $this->routes[$route] ;
            }
        }

        return null ;
    }

    /**
     * Get the config for the given key.
     *
     * @param string $key
     * @param string|null $default
     * @param bool $boolean
     * @return string|bool
     */
    protected function getConfig(string $key, string $default = null, bool $boolean = false) {
        $config = config('app.exceptions.'.$key) ;
        $value  = is_null($config) ? $default : $config ;

        return $boolean ? boolval($value) : $value ;
    }

    /**
     * Get the exception server base URL.
     *
     * @return bool|string|null
     */
    public function getBaseURL() {
        return $this->getConfig('url', $this->base) ;
    }

}
