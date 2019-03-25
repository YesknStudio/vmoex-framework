<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 21:19:45
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Announce;
use Yeskn\MainBundle\Form\Type\TinyHtmlTextareaType;

class AnnounceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TinyHtmlTextareaType::class, [
            'label' => '内容',
            'height' => '100',
            'required' => true,
        ]);
        $builder->add('show', CheckboxType::class, [
            'label' => '启用',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announce::class,
        ]);
    }
}
