<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 19:11:35
 */

namespace Yeskn\MainBundle\Form\Logic;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Yeskn\Support\ParameterBag;
use Yeskn\MainBundle\Entity\Options;

class OptionsLogic
{
    /**
     * @var EntityManager
     */
    private $em;

    private $basicOptions;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        $this->basicOptions = [
            'siteLogo',
            'siteSince',
            'siteVersion',
            'siteAnnounce',
        ];
    }

    public function getOptions(array $names)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Options');

        $options = [];

        foreach ($names as $name) {
            $entity = $repo->findOneBy(['name' => $name]);
            $value = $this->retrieveOption($name, $entity ? $entity->getValue() : null);
            $options[$name] = $value;
        }

        return new ParameterBag($options);
    }

    public function setOptions(array $options)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Options');

        foreach ($options as $name => $value) {
            $entity = $repo->findOneBy(['name' => $name]) ?: new Options();

            $entity->setName($name);

            $entity->setValue($this->fallbackOption($name, $value));

            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }

    private function retrieveOption($key, $value)
    {
        if ($key == 'siteSince') {
            return new \DateTime($value);
        }

        if (in_array($key, ['siteAnnounce', 'girl_enable'])) {
            return boolval($value);
        }

        return $value;
    }

    private function fallbackOption($key, $value)
    {
        if (in_array($key, ['siteAnnounce', 'girl_enable'])) {
            return intval($value);
        }

        if ($key == 'siteSince') {
            /** @var \DateTime $value */
            return $value->format('Y-m-d');
        }

        return $value;
    }


    /**
     * @return ParameterBag
     */
    public function getBasicOptions()
    {
        return $this->getOptions($this->basicOptions);
    }

    public function setBasicOptions(array $options)
    {
        return $this->setOptions($options);
    }
}
