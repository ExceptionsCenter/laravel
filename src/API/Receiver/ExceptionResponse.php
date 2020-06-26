<?php

namespace ExceptionsCenter\Laravel\API\Receiver;

use Monolog\Logger;
use ExceptionsCenter\Laravel\API\ExceptionLog;

/**
 * Class ExceptionResponse
 * @package ExceptionsCenter\Laravel\API\Receiver
 *
 * @property string message
 * @property int    code
 *
 * @author: Damien MOLINA
 */
class ExceptionResponse {

    /**
     * Message given by the response
     *
     * @var string
     */
    protected $message = "" ;

    /**
     * Code given by the response
     *
     * @var int
     */
    protected $code = 0 ;

    /**
     * Determine whether the response is successful
     *
     * @return bool
     */
    public function isSuccessful() {
        return $this->code == 0 ;
    }

    /**
     * Determine whether the response is not successful
     *
     * @return bool
     */
    public function isNotSuccessful() {
        return ! $this->isSuccessful() ;
    }

    /**
     * Manage the Exception's Center response
     *
     * @return void
     */
    public function manage() {
        if($this->isNotSuccessful()) {
            ExceptionLog::make($this->code . ' : ' . $this->message)
                ->level(Logger::ALERT)
                ->send() ;
        }
    }

    /**
     * @param string $response
     */
    public function __construct(string $response) {
        $arr = json_decode($response, true) ;

        $this->message  = $arr['message'] ?? "" ;
        $this->code     = $arr['code']    ?? "" ;
    }

}
