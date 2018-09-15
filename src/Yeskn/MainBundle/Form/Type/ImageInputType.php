<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 01:00:36
 */

namespace Yeskn\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageInputType extends AbstractType implements FormTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getParent()
    {
        return FileType::class;
    }
}