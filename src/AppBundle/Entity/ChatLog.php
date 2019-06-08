<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 6/8/2019
 * Time: 2:22 PM
 */

namespace AppBundle\Entity;


class ChatLog
{

    /**
     * @OneToOne(targetEntity="Room",mappedBy="ChatLog")
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
    private $timestamp;


}