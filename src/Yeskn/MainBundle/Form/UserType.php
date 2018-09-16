<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 14:10:23
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Form\Type\ImageInputType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'label' => '用户名',
            'required' => true,
        ]);

        $builder->add('nickname', TextType::class, [
            'label' => '昵称',
            'required' => true
        ]);

        $builder->add('password', TextType::class, [
            'label' => '密码',
            'required' => false,
            'attr' => [
                'placeholder' => '不修改此项时请不填'
            ]
        ]);

        $builder->add('email', EmailType::class, [
            'label' => '邮箱',
            'required' => true,
        ]);

        $builder->add('remark', TextareaType::class, [
            'label' => '签名',
            'required' => false
        ]);

        $builder->add('role', ChoiceType::class, [
            'label' => '角色',
            'expanded' => false,
            'multiple' => false,
            'choices' => [
                '普通用户' => 'ROLE_USER',
                '管理员' => 'ROLE_ADMIN',
                '超级管理员' => 'ROLE_SUPER_ADMIN'
            ]
        ]);

        $builder->add('avatar', ImageInputType::class, [
            'label' => '头像',
            'required' => false
        ]);

        $builder->add('gold', IntegerType::class, [
            'label' => '金币',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}