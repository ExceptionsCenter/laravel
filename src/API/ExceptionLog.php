<?php

namespace ExceptionsCenter\Laravel\API;

use Throwable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class ExceptionLog
 * @package ExceptionsCenter\Laravel\API
 *
 * @property int    level
 * @property string content
 *
 * @author: Damien MOLINA
 */
class ExceptionLog {

    /**
     * Level of the log line
     *
     * @var int
     */
    protected $level = Logger::DEBUG ;

    /**
     * Content of the log line
     *
     * @var string
     */
    protected $content = "" ;

    /**
     * Set the level of the log line
     *
     * @param int $level
     * @return $this
     */
    public function level(int $level) {
        $this->level = $level ;

        return $this ;
    }

    /**
     * Make a new instance of ExceptionLog
     *
     * @param string $msg
     * @return ExceptionLog
     */
    public static function make(string $msg) {
        return new static($msg) ;
    }

    /**
     * Add the log line to the exception log file
     *
     * @return void
     */
    public function send() {
        try {
            $log = new Logger('exceptions') ;

            $log->pushHandler(
                new StreamHandler(storage_path('logs/exceptions.log'))
            ) ;

            $log->addRecord(
                $this->level, $this->content, []
            ) ;
        } catch(Throwable $throwable) {}
    }

    /**
     * @param string $msg
     */
    public function __construct(string $msg) {
        $this->content = $msg ;
    }

}
