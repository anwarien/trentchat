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

    public function loadMessages() {



    }

}