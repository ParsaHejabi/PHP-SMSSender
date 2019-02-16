<?php
/**
 * Created by PhpStorm.
 * User: Parsa
 * Date: 2/14/2019
 * Time: 7:52 PM
 */

namespace App\Helper;

use Exception;
use InvalidArgumentException;

class ApiConnection
{
    private $baseurl;
    private $endpoint;

    /**
     * ApiConnection constructor.
     * @param $baseurl
     */
    public function __construct($baseurl)
    {
        if(!filter_var($baseurl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid base url');
        }

        $this->baseurl = $baseurl;
    }

    /**
     * @param $number
     * @param $body
     * @throws Exception
     */
    public function sendSms($number, $body)
    {
        if(!preg_match('/09[0-9]{9}/', $number)) {
            throw new InvalidArgumentException('Invalid phone number');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->baseurl . $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'number' => $number,
            'body' => $body
        ]));

        $response = curl_exec($ch); // If the API responds with some kind of tracking id etc.
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close ($ch);

        if($status != 200) {
            throw new Exception('API server error: ' . $response);
        }
    }
}