<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 13:10:11
 */

namespace Yeskn\AdminBundle\Form\SearchForm;

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Yeskn\MainBundle\Form\Type\DatetimeRangeType;

class SearchUserType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm( $builder, $options);

        $builder->add('id', SearchType::class, [
            'label' => 'ID',
            'required' => false
        ]);
        $builder->add('username', SearchType::class, [
            'label' => '用户名',
            'required' => false
        ]);
        $builder->add('registerAt', DatetimeRangeType::class, [
            'label' => '注册时间',
            'field_type' => 'date',
            'required' => false,
        ]);
        $builder->add('nickname', SearchType::class, [
            'label' => '昵称',
            'required' => false,
        ]);
        $builder->add('email', SearchType::class, [
            'label' => '邮箱',
            'required' => false,
        ]);

    }
}
