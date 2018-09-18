<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-19 00:50:19
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Goods;
use Yeskn\MainBundle\Form\Type\ImageInputType;

class GoodsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Goods $entity */
        $entity = $builder->getData();

        $builder->add('title', TextType::class);
        $builder->add('cover', ImageInputType::class, [
            'required' => $entity->getCover() ? false: true
        ]);
        $builder->add('detail', TextareaType::class);
        $builder->add('amount', IntegerType::class);
        $builder->add('price', NumberType::class);
        $builder->add('postFee', NumberType::class);
        $builder->add('count', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Goods::class
        ]);
    }
}