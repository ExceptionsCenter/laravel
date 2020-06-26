<?php

namespace ExceptionsCenter\Laravel\API\Sender;

use Throwable;
use Illuminate\Http\Request;

/**
 * Class ExceptionFormatter
 * @package ExceptionsCenter\Laravel\API\Sender
 *
 * @property array  parameters
 *
 * @author: Damien MOLINA
 */
class ExceptionFormatter {

    /**
     * Parameters of the formatter
     *
     * @var array
     */
    protected $parameters = [] ;

    /**
     * Define how many lines should be included before the problematic one
     *
     * @var int
     */
    public const NBR_LINE = 5 ;

    /**
     * Set a parameter at the given key
     *
     * @param string $key
     * @param string|array $value
     */
    public function setParameter(string $key, $value) {
        $this->parameters[$key] = $value ;
    }

    /**
     * Return the array of formatted parameters
     *
     * @return array
     */
    public function parameters() {
        return $this->parameters ;
    }

    /**
     * Return an array of the formatted exception data
     *
     * @param Throwable $e
     * @return array
     */
    protected function formatException(Throwable $e) {
        return [
            'code'      => $e->getCode(),
            'line'      => $e->getLine(),
            'message'   => $e->getMessage(),
            'file'      => $e->getFile(),
            'name'      => get_class($e),
        ] ;
    }

    /**
     * @param string $link
     * @param int $line
     * @return array
     */
    protected function formatFileContent(string $link, int $line) {
        $arr = [] ;

        try {
            $file   = file($link) ;
            $i      = $line - static::NBR_LINE ;

            while($i < min($line + static::NBR_LINE, count($file))) {
                $arr[] = $file[$i] ;
                $i++ ;
            }
        } catch(Throwable $e) {
            $arr = [$e->getMessage()] ;
        }

        return $arr ;
    }

    /**
     * @param Request|null $request
     * @return array
     */
    public function formatRequest($request = null) {
        if(is_null($request)) {
            return [] ;
        }

        try {
            $route = $request->route() ;

            if(is_null($route)) {
                return [] ;
            }

            $action = $route->getAction() ;
            $values = [
                'host'       => $request->header('host'),
                'user-agent' => $request->header('user-agent'),
                'cookie'     => $request->cookies->all(),
                'file'       => $request->file(),
                'session'    => $request->session()->all(),
                'ip'         => $request->getClientIp(),
                'route'      => [
                    'locale'     => $request->getLocale(),
                    'url'        => $request->getUri(),
                    'name'       => $route->getName(),
                    'method'     => $request->getMethod(),
                    'parameters' => $route->parameters(),
                    'middleware' => $route->middleware(),
                    'controller' => class_basename($action['controller']),
                ],
            ] ;
        } catch(Throwable $e) {
            $values = [$e->getMessage()] ;
        }

        return $values ;
    }

    /**
     * @param Throwable $e
     * @param Request   $request
     */
    public function __construct(Throwable $e, $request = null) {
        // Format the exception data
        $this->setParameter(
            'exception', $this->formatException($e)
        ) ;

        // Format the corresponding file data
        $this->setParameter(
            'content', $this->formatFileContent($e->getFile(), $e->getLine())
        ) ;

        // Format the request
        $this->setParameter(
            'request', $this->formatRequest($request)
        ) ;
    }

}
