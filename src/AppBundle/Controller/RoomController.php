<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 4/29/2019
 * Time: 3:35 PM
 */

namespace AppBundle\Controller;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Room;
use RandomLib\Factory;
use SecurityLib;

class RoomController extends Controller
{



    /**
     * @Route("/chat/createroom", name="createroom")
     */
    public function createRoomAction(Request $request) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $room = new Room();

        $form = $this->createFormBuilder($room)
            ->add('roomName', TextType::class)
            ->add('roomType',ChoiceType::class,[
                'choices'=> [
                    'Public' => 0,
                    'Private'=> 1,
                ]
            ])
            ->add('save',SubmitType::class, array('label'=>'Submit'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$room` variable has also been updated
            $room = $form->getData();
            // if the room is private, generate a random role for the room
            if ($room->getRoomType() == 1) {
            $factory = new Factory();
            $generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));
            $roomRole = 'ROLE_'.strtoupper($generator->generateString(5));
            $room->setRoomRole($roomRole);
            $user->addRole($roomRole);
        }
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();

            return $this->redirectToRoute('roomlist');
        }


        return $this->render('chat/createroom.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/chat/removeroomrole", name="removeroomrole")
     * @Method("GET")
     */
    public function removeRoomRoleAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->removeRole($request->attributes->get('role'));
        $em->flush();
        dump($request->attributes->get('role'));
        return $this->redirectToRoute('roomlist');
    }

    /**
     * @Route("/chat/rooms", name="roomlist")
     */
    public function showRoomListAction() {

        //displays list of chatrooms to go to

        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository('AppBundle:Room')->findAll();
        return $this->render('chat/roomlist.html.twig', [
            'rooms' => $rooms,
        ]);

    }
}