<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-22 17:13:15
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Form\Entity\ChangePassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class);
        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'first_options'  => array('label' => 'New Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class
        ]);
    }
}