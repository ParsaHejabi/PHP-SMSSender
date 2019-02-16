<?php
/**
 * Created by PhpStorm.
 * User: Parsa
 * Date: 2/15/2019
 * Time: 3:27 PM
 */

namespace App\Message;

class SmsMessage
{
    private $number;
    private $body;
    private $logId;

    public function __construct(string $number, string $body,int $logId)
    {
        $this->number = $number;
        $this->body = $body;
        $this->logId = $logId;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return integer
     */
    public function getLogId()
    {
        return $this->logId;
    }
}