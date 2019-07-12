<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 6/19/2019
 * Time: 1:55 PM
 */

namespace AppBundle\Services;
use AppBundle\Entity\Message;
use AppBundle\Entity\Room;
use DateTime;
use Psr\Container\ContainerInterface;



class MessageHandler
{
    /** @var ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function storeMessage($msgJson) {
            echo "storeMessage()\n";
        try {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $msgInfo = json_decode($msgJson);
            //print_r($msgInfo);
            $message = new Message();
            $message->setMessage($msgInfo->message);
            $room = $em->getRepository('AppBundle:Room')->findOneBy(array('id'=>$msgInfo->roomId));
            //echo gettype($room);
            $message->setRoom($room->getId());
            $message->setUserId($msgInfo->userId);
            $dateTime = new DateTime();
            $message->setTimeStamp($dateTime->format('m-d-Y H:i:s'));
            $em->persist($message);
            $em->flush();
        }
        catch (Exception $e) {
            echo "Error at ".$e->getLine().":".$e->getMessage();
        }

    }

    public function loadMessages($roomId) {
        // TODO only load last 20 messages for when rooms have a lot of messages
        // User joins chat room
        // Chat messages are loaded from room by room ID
        // room ID needed for room
        // use doctrine to load each message entity up
        // convert timestamp to fit interface time format
        // store in array
        // return array to be used to interface



    }

}