<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 10/12/2017
 * Time: 9:55 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class ChatController extends Controller
{

    /**
     * @Route("/chat/{chatroom}")
     */
    public function showAction($chatroom) {

        $template = $this->container->get('templating');
        $html = $template->render('chat/chat.html.twig',
            ['chat'=> $chatroom]);
        return new Response($html);
    }
}