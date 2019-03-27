<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
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
        $creating = !(boolean)$builder->getData()->getId();

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
            'required' => $creating,
            'attr' => !$creating ? ['help' => '不修改此项时请不填'] : []
        ]);

        $builder->add('email', EmailType::class, [
            'label' => '邮箱',
            'required' => true,
        ]);

        $builder->add('remark', TextareaType::class, [
            'label' => '签名',
            'required' => false,
        ]);

        $builder->add('role', ChoiceType::class, [
            'label' => '角色',
            'expanded' => false,
            'multiple' => false,
            'choices' => [
                '普通用户' => 'ROLE_USER',
                '管理员' => 'ROLE_ADMIN',
                '超级管理员' => 'ROLE_SUPER_ADMIN'
            ],
            'attr' => [
                'help' => '不能选择比当前角色等级高的角色'
            ]
        ]);

        $builder->add('avatar', ImageInputType::class, [
            'label' => '头像',
            'required' => false,
            'height' => 200,
            'width' => 200
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
