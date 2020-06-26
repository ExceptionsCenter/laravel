<?php

namespace ExceptionsCenter\Laravel\Exceptions;

use Exception;

/**
 * Class CheckExceptionCenterException
 * @package ExceptionsCenter\Laravel\Exceptions
 *
 * @author: Damien MOLINA
 */
class CheckExceptionCenterException extends Exception {

    public function __construct() {
        parent::__construct("Enjoy your first incoming exception") ;
    }

}
