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
        try {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $msgInfo = json_decode($msgJson);
            print_r($msgInfo);
            $message = new Message();
            $message->setMessage($msgInfo->message);
            $room = $em->getRepository('AppBundle:Room')->findOneBy(array('id'=>$msgInfo->roomId));
            $message->setRoom($room);
            $message->setUserId($msgInfo->userId);
            $dateTime = new DateTime();
            $message->setTimeStamp($dateTime->format('m-d-Y H:i'));
            $em->persist($message);
            $em->flush();
        }
        catch (Exception $e) {
            echo "Error at ".$e->getLine().":".$e->getMessage();
        }

    }

    public function loadMessages($json) {
        // TODO only load last 20 messages for when rooms have a lot of messages
        $em = $this->container->get('doctrine.orm.entity_manager');
        $msgList = array(
            'command' => 'loadMessages',
            'messages'=> array());
        $msgInfo = json_decode($json);

        $roomId = $msgInfo->roomId;
        $messages = $em->getRepository('AppBundle:Message')->findBy(array('room'=>$roomId));

        try{
            foreach ($messages as $message) {
                $user = $em->getRepository('AppBundle:User')->findOneBy(array('id'=>$message->getUserId()));
                if (!$user) echo "User is null\n";
                $username = $user->getUsername();
                $msg = $message->getMessage();
                $time = $message->getTimeStamp();

                array_push($msgList['messages'],['message'=> $username.$msg,
                    'timestamp'=>$time]);
            }
        }
        catch (Exception $e){
            echo "Error at ".$e->getLine().":".$e->getMessage();
        }

        return json_encode($msgList);
    }


}