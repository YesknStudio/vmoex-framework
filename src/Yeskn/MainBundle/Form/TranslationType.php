<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 14:59:07
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Translation;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Translation $entity */
        $entity = $builder->getData();

        $builder->add('messageId', TextType::class, [
            'label' => '词条Id',
            'attr' => [
                'readonly' => $entity ? (!$entity->isCanDelete()) : false
            ]
        ]);
        $builder->add('chinese', null, ['label' => '中文']);
        $builder->add('english', null, ['label' => '英文']);
        $builder->add('japanese', null, ['label' => '日文']);
        $builder->add('taiwanese', null, ['label' => '中文繁体']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Translation::class
        ]);
    }
}
