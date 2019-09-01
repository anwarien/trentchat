<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 3/26/2018
 * Time: 1:42 PM
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity;
/**
 * Class Room
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoomRepository")
 * @ORM\Table(name="room")
 */
class Room
{

    private static $PUBLIC = 0;
    private static $PRIVATE = 1;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $roomName;


    /**
     * @ORM\Column(type="integer")
     */
    private $roomType;


    /**
     * @ORM\Column(type="string")
     */
    private $roomRole;


    /**
     * @ORM\OneToMany(targetEntity="Message",mappedBy="room")
     */
    private $messages;



    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getRoomRole()
    {
        return $this->roomRole;
    }

    /**
     * @param mixed $roomRole
     */
    public function setRoomRole($roomRole)
    {
        $this->roomRole = $roomRole;
    }

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection|Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }






}