<?php

namespace ExceptionsCenter\Laravel\API\Sender;

use Throwable;
use Illuminate\Foundation\Auth\User;

/**
 * Class LaravelExceptionSender
 * @package ExceptionsCenter\Laravel\API\Sender
 *
 * @author: Damien MOLINA
 */
class LaravelExceptionSender extends AbstractExceptionSender {

    /**
     * Determine whether a user is logged in.
     *
     * @return bool
     */
    public function userIsLoggedIn() {
        return auth()->check() ;
    }

    /**
     * Generate information concerning the current user
     * if It is a guest
     *
     * @return array
     */
    public function getGuestInformation() {
        return [
            "The user isn't logged in"
        ] ;
    }

    /**
     * This method should return an array of the data
     * concerning the user who made the request. Please,
     * be careful regarding the data you send.
     *
     * @return array
     */
    public function getUserInformation() {
        /** @var User $user */
        $user = auth()->user() ;

        return $user->toArray() ;
    }

    /**
     * Determine whether the given throwable element should
     * be reported to the Exception's center.
     *
     * @param Throwable|null $e
     * @return bool
     */
    public function shouldBeReported(Throwable $e = null) {
        return ! is_null($e)
            && ! is_null($this->getProjectKey())
            && $this->isEnabled()
            && $this->expectedToBeReported($e) ;
    }

    /**
     * @param Throwable $throwable
     * @param null $request
     * @return LaravelExceptionSender
     */
    public static function make(Throwable $throwable, $request = null) {
        $self            = new LaravelExceptionSender() ;
        $self->exception = $throwable ;
        $self->request   = $request ;

        return $self ;
    }

}
