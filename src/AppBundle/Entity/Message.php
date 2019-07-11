<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 6/8/2019
 * Time: 2:22 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity;
use AppBundle\Entity\Room;
/**
 * Class Message
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class Message
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Room",mappedBy="Message")
     * @ORM\Column(type="integer")
     */
    private $room;



    /**
     * @ORM\Column(type="string")
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
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

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    public function getId(){
        return $this->id;
    }


}