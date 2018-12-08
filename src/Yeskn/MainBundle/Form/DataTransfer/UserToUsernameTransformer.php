<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 18:20:03
 */

namespace Yeskn\MainBundle\Form\DataTransfer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Yeskn\MainBundle\Entity\User;

class UserToUsernameTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transform($value)
    {
        if ($value instanceof User) {
            return $value->getUsername();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $repo = $this->em->getRepository('YesknMainBundle:User');

        return $repo->findOneBy(['username' => $value]);
    }
}
