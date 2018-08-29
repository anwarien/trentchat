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
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $roomName;

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