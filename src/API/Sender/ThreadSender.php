<?php

namespace ExceptionsCenter\Laravel\API\Sender;

use Thread;

/**
 * Class ThreadSender
 * @package ExceptionsCenter\Laravel\API\Sender
 *
 * @property AbstractExceptionSender sender
 *
 * @author: Damien MOLINA
 */
class ThreadSender extends Thread {

    /**
     * @var AbstractExceptionSender
     */
    private $sender ;

    /**
     * @param AbstractExceptionSender $sender
     */
    public function __construct(AbstractExceptionSender $sender) {
        $this->sender = $sender ;
    }

    /**
     * Run the thread generating a request
     * to the Exceptions' Center.
     *
     * @return void
     */
    public function run() {
        $this->sender->generateRequest() ;
    }

}
