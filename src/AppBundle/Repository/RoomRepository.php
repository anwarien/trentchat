<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 8/28/2019
 * Time: 2:57 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RoomRepository extends  EntityRepository
{

    /**
     * @return Room[]
     */
    public function findAllPrivateRooms(){

        return $this->createQueryBuilder('room') // SELECT * FROM room as room
            ->andWhere('room.roomType = :roomType') // :roomType indicates a parameter
            ->setParameter('roomType',1)
            ->orderBy('room.roomName','DESC')
            ->getQuery()
            ->execute();

    }

}