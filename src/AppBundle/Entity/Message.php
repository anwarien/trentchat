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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
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
     * @ORM\ManyToOne(targetEntity="Room",inversedBy="messages")
     *
     */
    private $room;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string")
     */
    private $message;

    /**
     * @ORM\Column(type="string")
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
     * @return Room $room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    public function getId(){
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


}