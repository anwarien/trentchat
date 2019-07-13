<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 11/15/2017
 * Time: 10:15 AM
 */

namespace AppBundle\WebSocket;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\MessageComponentInterface;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\MessageHandler;


class ChatSocket implements MessageComponentInterface {


    protected $clients;
    private $em;
    /** @var  ContainerInterface $container */
    private $container;
    private $subscriptions;
    private $users;
    private $online =  [];
    private $messageHandler;



    public function __construct(EntityManager $em,ContainerInterface $container)
    {
        $this->clients = new \SplObjectStorage;
        $this->em = $em;
        $this->container = $container;
        $this->subscriptions = [];
        $this->users = [];
        $this->messageHandler = $this->container->get('message.handler');
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "connection ({$conn->resourceId}) has logged in!\n";
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;

       echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        //converts stdClass arr to assoc arr
         $data = json_decode($msg,true);

         //print_r($data);
        switch ($data['command']) {
            case "subscribe":
                $this->subscriptions[$from->resourceId] = $data['channel'];
                break;
            case "message":
                if (isset($this->subscriptions[$from->resourceId])) {
                    $target = $this->subscriptions[$from->resourceId];
                    foreach ($this->subscriptions as $id=>$channel) {
                        if ($channel == $target) {
                            $this->users[$id]->send($msg);
                            $this->messageHandler->storeMessage($msg);
                        }
                    }
                }
                break;
            case "connect":
                if (isset($this->subscriptions[$from->resourceId])) {
                    //target is the chatroom you want to send msg to
                    $target = $this->subscriptions[$from->resourceId];

                    //load previous messages from chat room upon connecting
                    $loadMsgJson =$this->messageHandler->loadMessages($msg);
                    $this->users[$from->resourceId]->send($loadMsgJson);

                    foreach ($this->subscriptions as $id=>$channel) {
                        if ($channel == $target) {
                            // creating key array for room and adding user to key
                            // else, just add user to key
                            if (!array_key_exists($channel,$this->online)) {
                                //create key, add user to key
                                //if room has special chars in it
                                $this->online[$channel] = array();
                                //array_push($this->online[$channel], array('id'=>$from->resourceId ,'user' => $data['user']));
                                $this->online[$channel][$from->resourceId] = array('id'=>$from->resourceId,'user'=>$data['user']);
                            }
                            else {
                                echo "Pushing user ". $data['user'] . " to online list\n";
                                //array_push($this->online[$channel], array('id'=>$from->resourceId ,'user' => $data['user']));
                                $this->online[$channel][$from->resourceId] = array('id'=>$from->resourceId,'user'=>$data['user']);
                            }
                            $this->users[$id]->send(json_encode(array("command"=>"online",
                                "list"=>$this->online[$channel])));
                            echo "\n ***JSON BEING SENT AS ONLINE LIST:" . json_encode($this->online[$channel]) . "***\n";
                            //convert 'user is online' messages to 'message' json
//                            $this->users[$id]->send(json_encode(array("command"=>"message",
//                                "message"=>$data['user'] . " has joined the room!")));
                            $joinMsg = json_decode($msg);
                            $messageStr = $data['user'] . " has joined the room!";
                            $joinRoomJson = json_encode(array("command"=>"message",
                                "message"=>$messageStr,
                                'roomId'=>$joinMsg->roomId,
                                'userId'=>$joinMsg->userId));
                            $this->users[$id]->send($joinRoomJson);
                            $joinMsg = json_decode($joinRoomJson);
                            $joinMsg->message = str_replace($data['user'],"", $messageStr);
                            $this->messageHandler->storeMessage(json_encode($joinMsg));
                        }
                    }
                }
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "client $conn->resourceId detatched\n";
        // TODO remove user based on roomId
        echo "\nsubscription array: ".$this->subscriptions[$conn->resourceId];
        if (isset($this->subscriptions[$conn->resourceId])) {
            echo "isset condition reached";
            $target = $this->subscriptions[$conn->resourceId];
            foreach ($this->subscriptions as $id=>$channel) {
                echo "\nChannel: $channel";
                echo "\nTarget: $target";
                if ($channel == $target) {
                    echo "\n***USER  IS BEING REMOVED***\n";
                    $this->users[$id]->send(json_encode(array("command"=>"message",
                        "message"=>$this->online[$channel][$conn->resourceId]['user']. " has left the room")));
                    unset($this->online[$channel][$conn->resourceId]);
                    print_r($this->online[$channel]);
                    $this->users[$id]->send(json_encode(array("command"=>"online",
                        "list"=>$this->online[$channel])));
                    //convert 'user is online' messages to 'message' json

                    //unset($this->subscriptions[$conn->resourceId]);
                    //unset($this->users[$conn->resourceId]);
                    echo "channel list:\n";
                    unset($this->subscriptions[$conn->resourceId]);
                    print_r($this->subscriptions);
                }
            }
        }
        else "isset condition not reached\n";
        unset($this->online[$conn->resourceId]);
        foreach ($this->clients as $client) {
            print_r($this->online);
            $client->send(json_encode($this->online));
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "AN ERROR HAS OCCURRED: {$e->getMessage()} LINE ".$e->getLine()."\n";
        $conn->close();
    }
}