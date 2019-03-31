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

class SearchTranslationType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm( $builder, $options);

        $builder->add('messageId', SearchType::class, [
            'label' => 'messageId',
            'required' => false
        ]);
        $builder->add('chinese', SearchType::class, [
            'label' => '中文',
            'required' => false
        ]);
        $builder->add('english', SearchType::class, [
            'label' => '英文',
            'required' => false
        ]);
        $builder->add('taiwanese', SearchType::class, [
            'label' => '中文繁体',
            'required' => false
        ]);

        $builder->add('japanese', SearchType::class, [
            'label' => '日语',
            'required' => false
        ]);
    }
}
