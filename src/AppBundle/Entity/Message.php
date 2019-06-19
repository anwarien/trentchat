<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 6/8/2019
 * Time: 2:22 PM
 */

namespace AppBundle\Entity;


class Message
{

    /**
     * @OneToOne(targetEntity="Room",mappedBy="Message")
     */
    private $room;

    /**
     * @Column(type="integer")
    */
    private $id;

    /**
     * @Column(type="string")
     */
    private $message;

    /**
     * @Column(type="string")
     */
    private $timeStamp;

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    public function setMessage($msg) {
        $this->message = $msg;
    }

    /**
     * @return mixed
     */
    public function getTimeStamp() {
        return $this->timeStamp;
    }

    public function setTimeStamp($timeStamp) {
        $this->timeStamp = $timeStamp;
    }
}