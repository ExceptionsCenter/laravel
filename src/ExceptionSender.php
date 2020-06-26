<?php

namespace ExceptionsCenter\Laravel;

use ExceptionsCenter\Laravel\API\Sender\LaravelExceptionSender ;

/**
 * Class ExceptionSender
 * @package ExceptionsCenter\Laravel
 *
 * @property bool   enabled
 * @property bool   multithreading
 * @property bool   log
 * @property string key
 *
 * @author: Damien MOLINA
 */
class ExceptionSender extends LaravelExceptionSender {

    /**
     * Project's key available on the Exception's Center website
     *
     * @var string
     */
    protected $key = "" ;

    /**
     * This value determines whether the exceptions
     * center is running.
     *
     * @var bool
     */
    protected $enabled = true ;

    /**
     * Determine whether threads could be used to send
     * the exceptions to the Exceptions' Center
     *
     * @var bool
     */
    protected $multithreading = true ;

    /**
     * Determine whether an error during the sending
     * should be saved in the storage/logs/exceptions.log
     * file.
     *
     * @var bool
     */
    protected $log = true ;

}
