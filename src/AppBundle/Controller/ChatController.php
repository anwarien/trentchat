<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 10/12/2017
 * Time: 9:55 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Room;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ChatController extends Controller
{

    /**
     * @Route("/",name="homepage")
     */
    public function homepageAction() {
        return $this->render('chat/login.html.twig');
    }



    /**
     * @Route("/chat/notes", name="notes")
     * @Method("GET")
     */
    public function chatNotesAction(){

        $userData = [
            ['id'=>1,'username' =>'john123'],
            ['id'=>2,'username' =>'dogboi3'],
            ['id'=>3,'username' =>'2-1ny'],
        ];

        return new JsonResponse($userData);

    }


    /**
     * @Route("/chat/room/{chatroom}", name="chatroom")
     */
    public function showAction($chatroom = "TEST_ROOM") {


        $em = $this->getDoctrine()->getManager();
        $room= $em->getRepository('AppBundle:Room')->findBy(array('roomName'=>$chatroom));
        if (!$room) return new Response('<html><body><h1>Room does not exist</h1></body></html>');

        $template = $this->container->get('templating');
        $html = $template->render('chat/chat.html.twig',
            ['chat'=> $chatroom]);
        $cache = $this->get('doctrine_cache.providers.my_cache');

        return new Response($html);
    }


    private function createWebSocket() {

    }

    /**
     * @Route("/admin",name="admin")
     */
    public function adminAction() {
        return new Response('<html><body><h1>Admins Only</h1></body></html>');
    }


}