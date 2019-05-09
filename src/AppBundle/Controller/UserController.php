<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 4/29/2019
 * Time: 3:36 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
class UserController extends Controller
{

    /**
     * @Route("/chat/newuser", name="newuser")
     */
    public function createUserAction() {

        /*
         * Takes user input and uses it to create a registered user
         * check if username exists
         * if email exists
         * if password and confirm password equal
         */
        $user = new User();
        $username= 'user'.rand(1,100);
        $user->setUserName($username);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('<html><body>User '.$username .' created!</body></html>');
    }



    /**
     * @Route("/chat", name="listusers")
     */
    public function listUsersAction() {

        $em = $this->getDoctrine()->getManager();
        //$users = $em->getRepository('AppBundle:User')->findAllVerified();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('chat/list.html.twig', [
            'users' => $users,
        ]);
    }





    /**
     * @Route("/profiles/{username}", name="profilepage")
     */
    public function displayProfileAction($username) {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(['username'=>$username]);

        if (!$user) throw $this->createNotFoundException('User not found');

        return  $this->render('chat/profile.html.twig' ,
            [ 'user'=> $user]
        );

    }



}