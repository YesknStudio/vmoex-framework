<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-28 16:05:29
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class BlogAddCnameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cname');
        $builder->add('confirm_dns', CheckboxType::class, [
            'required' => true,
            'label' => 'user.blog.i_confirm_that_added_cname_dns'
        ]);
    }
}
