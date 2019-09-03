<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 8/28/2019
 * Time: 3:48 PM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Entity\Room;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{


    /**
     * @param User $user
     * @return Message[]
     */
    public function findMessagesByUser(User $user) {

        return $this->createQueryBuilder('message')
            ->andWhere('message.userId = :userId')
            ->setParameter('userId',$user)
            ->getQuery()
            ->execute();

    }


    /**
     * @param Room $room
     * @return Message[]
     */
    public function findMessagesByRoom(Room $room) {
        return $this->createQueryBuilder('message')
            ->andWhere('message.room = :roomId')
            ->setParameter('roomId', $room)
            ->leftJoin('message.room','room_message') // room_message is alias for referencing the joined table
            ->getQuery()
            ->execute();
    }


}