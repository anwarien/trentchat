<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 10/12/2017
 * Time: 9:55 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ChatController extends Controller
{
    /**
     * @Route("/chat/newuser", name="newuser" )
     */
    public function createUserAction() {

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

    /**
     * @Route("/chat/notes", name="notes")
     * @Method("GET")
     */
    public function chatNotesAction(){

        $userData = [
            ['id'=>1,'username' =>'john123'],
            ['id'=>2,'username' =>'doggyboi3'],
            ['id'=>3,'username' =>'21igk'],
        ];

        return new JsonResponse($userData);

    }
    /**
     * @Route("/chat/{chatroom}")
     */
    public function showAction($chatroom) {

        $template = $this->container->get('templating');
        $html = $template->render('chat/chat.html.twig',
            ['chat'=> $chatroom]);
        $cache = $this->get('doctrine_cache.providers.my_cache');

        return new Response($html);
    }
}