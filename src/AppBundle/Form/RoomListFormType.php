<?php
/**
 * Created by PhpStorm.
 * User: Trent
 * Date: 9/4/2019
 * Time: 9:17 PM
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RoomListFormType
 * @package AppBundle\Form
 */
class RoomListFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options = []) {

        $builder->add('name')
            ->add('type');

    }


    public function configureOptions(OptionsResolver $resolver) {

    }

}