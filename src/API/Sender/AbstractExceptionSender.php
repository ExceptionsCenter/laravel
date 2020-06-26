<?php

namespace ExceptionsCenter\Laravel\API\Sender;

use Throwable;
use Monolog\Logger;
use Illuminate\Http\Request;
use ExceptionsCenter\Laravel\API\ExceptionLog;
use ExceptionsCenter\Laravel\API\Request\cUrl;
use ExceptionsCenter\Laravel\API\UrlFormatter;
use ExceptionsCenter\Laravel\API\Contracts\Reportable;
use ExceptionsCenter\Laravel\API\Receiver\ExceptionResponse;
use ExceptionsCenter\Laravel\API\Contracts\ExceptionUserContract;

/**
 * Class AbstractExceptionSender
 * @package ExceptionsCenter\Laravel\API\Sender
 *
 * @property Throwable  exception
 * @property bool       enabled
 * @property bool       log
 * @property bool       multithreading
 * @property string     key
 * @property Request    request
 *
 * @author: Damien MOLINA
 */
abstract class AbstractExceptionSender implements Reportable, ExceptionUserContract {

    use HasExceptedParameters, UrlFormatter ;

    /**
     * @var Throwable
     */
    protected $exception ;

    /**
     * @var Request
     */
    protected $request ;

    /**
     * State of the sender.
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

    /**
     * Project's key available on the Exceptions' Center
     * website.
     *
     * @var string
     */
    protected $key = "" ;

    /**
     * Get the config for the given key.
     *
     * @param string $key
     * @param string $default
     * @param bool $boolean
     * @return string|bool
     */
    private function getConfig(string $key, string $default, bool $boolean = false) {
        $config = config('app.exceptions.'.$key) ;
        $value  = is_null($config) ? $default : $config ;

        return $boolean ? boolval($value) : $value ;
    }

    /**
     * Determine whether the exception sender is running.
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->getConfig('enabled', $this->enabled, true) ;
    }

    /**
     * Determine whether the multithreading is
     * enabled to this project.
     *
     * @return bool
     */
    public function isMultithreadingEnabled() {
        return $this->getConfig('multithreading', $this->multithreading, true) ;
    }

    /**
     * Determine whether an error should be
     * saved.
     *
     * @return bool
     */
    private function isLogEnabled() {
        return $this->getConfig('log', $this->log, true) ;
    }

    /**
     * Get the project's key.
     *
     * @return string
     */
    public function getProjectKey() {
        return $this->getConfig('key', $this->key) ;
    }

    /**
     * Add the given string to the exception log file.
     *
     * @param string $msg
     */
    protected function addLogAlert(string $msg) {
        try {
            if($this->isLogEnabled()) {
                ExceptionLog::make($msg)->level(Logger::ALERT)->send() ;
            }
        } catch(Throwable $throwable) {}
    }

    /**
     * Execute the request using cURL.
     *
     * @param array $parameters
     * @param string $url
     * @return ExceptionResponse
     */
    public function makeRequest(array $parameters, string $url) {
        return new ExceptionResponse(
            cURL::make($url, $parameters)
        ) ;
    }

    /**
     * Generate the request.
     *
     * @return bool
     */
    public function generateRequest() {
        if(! is_null($this->exception)) {
            try {
                if($this->shouldBeReported($this->exception)) {
                    /*
                     * Start to format the exception and the request
                     */
                    $formatter = new ExceptionFormatter($this->exception, $this->request) ;

                    // Set the key of the project
                    $formatter->setParameter(
                        'project_key', $this->getProjectKey()
                    );

                    // Set the user data
                    $formatter->setParameter(
                        'user', $this->userIsLoggedIn() ? $this->getUserInformation() : $this->getGuestInformation()
                    ) ;

                    $response = $this->makeRequest(
                        $formatter->parameters(), $this->url('send')
                    ) ;

                    $response->manage() ;

                    return $response->isSuccessful() ;
                }
            } catch(Throwable $e) {
                $this->addLogAlert($e->getMessage()) ;
            }
        }

        return false ;
    }

    /**
     * This method send a throwable element to the exception's
     * center. Returns whether the given exception had been
     * successfully sent.
     *
     * @return bool
     */
    public function send() {
        if($this->isMultithreadingEnabled()) {
            try {
                $thread = new ThreadSender($this) ;
                $thread->start() ;

                return true ;
            } catch(Throwable $throwable) {}
        }

        return $this->generateRequest() ;
    }

}
