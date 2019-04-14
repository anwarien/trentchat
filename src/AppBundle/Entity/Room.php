<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 3/26/2018
 * Time: 1:42 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 * @ORM\Entity
 * @ORM\Table(name="room")
 */

class Room
{

    private static $PUBLIC = 0;
    private static $PRIVATE = 2;
    private static $INVITE = 1;



    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $roomName;


    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable = false)
     */
    private $roomType;

    /**
     * @return mixed
     */
    public function getRoomType()
    {
        return $this->roomType;
    }

    /**
     * @param mixed $roomType
     */
    public function setRoomType($roomType)
    {
        $this->roomType = $roomType;
    }

    /**
     * @return string
     */
    public function getRoomName()
    {
        return $this->roomName;
    }

    /**
     * @param string $roomName
     */
    public function setRoomName($roomName)
    {
        $this->roomName = $roomName;
    }
}