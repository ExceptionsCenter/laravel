<?php

namespace ExceptionsCenter\Laravel\API\Request;

/**
 * Class cUrl
 * @package ExceptionsCenter\Laravel\API\Request
 *
 * @author: Damien MOLINA
 */
class cUrl {

    /**
     * make a cURL request
     *
     * @param string $url
     * @param array $param
     * @return bool|string
     */
    public static function make(string $url, array $param) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch,[CURLOPT_HTTPHEADER=>["Content-type: application/x-www-form-urlencoded"]]);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(!is_null($param)) {
            curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($param));
        }
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ;
    }

}
