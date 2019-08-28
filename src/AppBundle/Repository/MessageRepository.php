<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 8/28/2019
 * Time: 3:48 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{



    public function findMessagesByUser($user) {

        return $this->createQueryBuilder('message')
            ->andWhere('message.userId = :userId')
            ->setParameter('userId',$user->getId())
            ->getQuery()
            ->execute();

    }


}