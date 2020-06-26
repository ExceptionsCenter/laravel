<?php

namespace ExceptionsCenter\Laravel\API\Contracts;

/**
 * Interface ExceptionUserContract
 * @package ExceptionsCenter\Laravel\API\Contracts
 *
 * @author: Damien MOLINA
 */
interface ExceptionUserContract {

    /**
     * This method should return an array of the data
     * concerning the user who made the request.
     * Please, be careful regarding the data you send.
     *
     * @return array
     */
    public function getUserInformation() ;

    /**
     * In case of there is not an actual logged in
     * user, this method return an array of interesting
     * information concerning the user
     *
     * @return array
     */
    public function getGuestInformation() ;

    /**
     * Determine whether a user is logged in.
     *
     * @return bool
     */
    public function userIsLoggedIn() ;

}
