<?php

namespace ExceptionsCenter\Laravel\API\Sender;

use Throwable;

/**
 * Trait HasExceptedParameters
 * @package ExceptionsCenter\Laravel\API\Sender
 *
 * @property array options
 * @property Throwable exception
 *
 * @author: Damien MOLINA
 */
trait HasExceptedParameters {

    /**
     * Set the exceptions the controller should apply to
     *
     * @param string|array $class
     * @return $this
     */
    public function only($class) {
        $this->options['only'] = is_array($class) ? $class : func_get_args();

        return $this ;
    }

    /**
     * Set the exceptions the controller should exclude
     *
     * @param string|array $class
     * @return $this
     */
    public function except($class) {
        $this->options['except'] = is_array($class) ? $class : func_get_args();

        return $this ;
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function expectedToBeReported(Throwable $e) {
        if(isset($this->options['only'])) {
            return $this->containException(
                (array) $this->options['only'], $e
            ) ;
        }

        if(isset($this->options['except'])) {
            return ! $this->containException(
                (array) $this->options['except'], $e
            ) ;
        }

        return true ;
    }

    /**
     * Check if the current exception instance is in the array
     *
     * @param array $array
     * @param Throwable $e
     * @return bool
     */
    protected function containException(array $array, Throwable $e) {
        return ! is_null(
            $this->containExceptionAsValue($e, $array)
        ) ;
    }

    /**
     * Check if the current exception instance is in the array
     *
     * @param Throwable $e
     * @param array $array
     * @return bool
     */
    protected function containExceptionAsValue(Throwable $e, array $array = []) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $foo = $this->containExceptionAsValue($e, $value) ;

                if(! is_null($foo)) {
                    return $key ;
                }
            } elseif ($e instanceof $value) {
                return $key ;
            }
        }

        return null ;
    }

}
