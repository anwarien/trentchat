<?php
// src/AppBundle/DataFixtures/ORM/Fixtures.php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$user = new User();
        $username= 'user'.rand(1,100);
        $user->setUserName($username);
        $manager->persist($user);
        $manager->flush();*/

        Fixtures::load(__DIR__.'/fixtures.yml',$manager);
    }
}