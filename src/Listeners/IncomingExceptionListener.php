<?php

namespace ExceptionsCenter\Laravel\Listeners;

use Throwable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use ExceptionsCenter\Laravel\ExceptionSender;
use ExceptionsCenter\Laravel\API\Sender\LaravelExceptionSender;

/**
 * Class IncomingExceptionListener
 * @package App\Listeners
 *
 * @author: Damien MOLINA
 */
class IncomingExceptionListener implements ShouldQueue {

    use InteractsWithQueue ;

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event) {
        try {
            if(method_exists($event, 'getException')) {
                $throwable = $event->getException() ;

                $method = null ;
                if(method_exists($event, 'getRequest')) {
                    $method = $event->getRequest() ;
                }

                $class = config('app.exceptions.model', ExceptionSender::class) ;

                if(is_null($class)) {
                    return ;
                }

                /** @var LaravelExceptionSender $center */
                $center = new $class ;
                $center->set($throwable, $method)->send() ;
            }
        } catch(Throwable $throwable) {}
    }

}
