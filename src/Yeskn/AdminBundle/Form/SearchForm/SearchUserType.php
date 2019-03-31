<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 13:10:11
 */

namespace Yeskn\AdminBundle\Form\SearchForm;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchUserType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm( $builder, $options);

        $builder->add('id', TextType::class, [
            'label' => 'ID',
            'required' => false
        ]);
        $builder->add('username', TextType::class, [
            'label' => '用户名',
            'required' => false
        ]);
        $builder->add('createdAt', DateTimeType::class, [
            'label' => '注册时间',
            'input' => 'string',
            'widget' => 'single_text',
            'with_seconds' => true,
            'required' => false,
        ]);
        $builder->add('nickname', TextType::class, [
            'label' => '昵称',
            'required' => false,
        ]);
        $builder->add('email', ChoiceType::class, [
            'label' => '邮箱',
            'required' => false,
        ]);

    }
}
