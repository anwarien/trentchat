<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 8/28/2019
 * Time: 3:48 PM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Message;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{


    /**
     * @param $user
     * @return Message[]
     */
    public function findMessagesByUser($user) {

        return $this->createQueryBuilder('message')
            ->andWhere('message.userId = :userId')
            ->setParameter('userId',$user->getId())
            ->getQuery()
            ->execute();

    }


}