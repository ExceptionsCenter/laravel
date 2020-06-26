<?php

namespace ExceptionsCenter\Laravel\API\Contracts;

use Throwable;
use ExceptionsCenter\Laravel\API\Receiver\ExceptionResponse;

/**
 * Interface Reportable
 * @package ExceptionsCenter\Laravel\API\Contracts
 *
 * @author: Damien MOLINA
 */
interface Reportable {

    /**
     * Determine whether the given throwable element should
     * be reported to the Exception's center.
     *
     * @param Throwable $e
     * @return bool
     */
    public function shouldBeReported(Throwable $e) ;

    /**
     * This method send a throwable element to the exception's
     * center. Returns whether the given exception had been
     * successfully sent.
     *
     * @return bool
     */
    public function send() ;

    /**
     * Make the request to the Exception's Center website with
     * the given data to the given formatted url
     *
     * @param array $parameters
     * @param string $url
     * @return ExceptionResponse
     */
    public function makeRequest(array $parameters, string $url) ;

}
