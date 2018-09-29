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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChatController extends Controller
{

    /**
     * @Route("/",name="homepage")
     */
    public function homepageAction() {
        return $this->render('chat/login.html.twig');
    }

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
     * @Route("/chat/newroom", name="newroom")
     */
    public function createRoomAction(Request $request) {

        $room = new Room();

        $form = $this->createFormBuilder($room)
            ->add('roomName', TextType::class)
            ->add('save',SubmitType::class, array('label'=>'Submit'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$room` variable has also been updated
            $room = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($room);
        $em->flush();

            return $this->redirectToRoute('roomlist');
        }


        return $this->render('chat/newroom.html.twig', array(
            'form' => $form->createView(),
        ));
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
     * @Route("/chat/rooms", name="roomlist")
     */
    public function showRoomListAction() {

        //displays list of chatrooms to go to

        $em = $this->getDoctrine()->getManager();
        //$users = $em->getRepository('AppBundle:User')->findAllVerified();
        $rooms = $em->getRepository('AppBundle:Room')->findAll();

        return $this->render('chat/roomlist.html.twig', [
            'rooms' => $rooms,
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
            ['id'=>2,'username' =>'dogboi3'],
            ['id'=>3,'username' =>'2-1ny'],
        ];

        return new JsonResponse($userData);

    }



    /**
     * @Route("/chat/room/{chatroom}", name="chatroom")
     */
    public function showAction($chatroom = "TEST_ROOM") {


        // TODO if $chatroom is empty, go to a page with list of available rooms
        $em = $this->getDoctrine()->getManager();
        $room= $em->getRepository('AppBundle:Room')->findBy(array('roomName'=>$chatroom));
        if (!$room) return new Response('<html><body><h1>Room does not exist</h1></body></html>');

        $template = $this->container->get('templating');
        $html = $template->render('chat/chat.html.twig',
            ['chat'=> $chatroom]);
        $cache = $this->get('doctrine_cache.providers.my_cache');

        return new Response($html);
    }


    /**
     * @Route("/admin",name="admin")
     */
    public function adminAction() {
        return new Response('<html><body><h1>Admins Only</h1></body></html>');
    }


}