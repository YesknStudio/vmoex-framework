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
use Symfony\Component\Filesystem\Filesystem;
use Yeskn\MainBundle\Form\OptionsGirlType;
use Yeskn\MainBundle\Form\OptionsMaintainType;
use Yeskn\Support\ParameterBag;
use Yeskn\MainBundle\Entity\Options;

class OptionsLogic
{
    /**
     * @var EntityManager
     */
    private $em;

    private $varDir;

    private $basicOptions = [
        'siteLogo',
        'siteSince',
        'siteVersion',
        'siteAnnounce',
    ];

    private $girlOptions = [
        'girl_enable'
    ];

    private $maintainOptions = [
        'maintain_enable', 'maintain_start', 'maintain_stop'
    ];

    private $groupNames = [
        'girl' => '看板娘',
        'maintain' => '维护模式'
    ];

    private $groupTypes = [
        'girl' => OptionsGirlType::class,
        'maintain' => OptionsMaintainType::class
    ];

    private $handlers = [
        'maintain' => 'handleSetMaintainOptions'
    ];

    public function __construct(EntityManagerInterface $entityManager, $projectDir)
    {
        $this->em = $entityManager;
        $this->varDir = rtrim($projectDir, '/') . '/var';
    }

    public function getGroupFormType($group)
    {
        return $this->groupTypes[$group];
    }

    public function getGroupName($group)
    {
        return $this->groupNames[$group];
    }

    public function getGroupOptionKeys($optionsName)
    {
        $group = $optionsName . 'Options';

        return $this->$group;
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

    public function setOptions(array $options, $group = null)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Options');

        if ($group && isset($this->handlers[$group])) {
            call_user_func([$this, $this->handlers[$group]], $options);
        }

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

        if (in_array($key, ['siteAnnounce', 'girl_enable', 'maintain_enable'])) {
            return boolval($value);
        }

        return $value;
    }

    private function fallbackOption($key, $value)
    {
        if (in_array($key, ['siteAnnounce', 'girl_enable', 'maintain_enable'])) {
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

    public function handleSetMaintainOptions(array $options)
    {
        $fs = new Filesystem();

        if (!$options['maintain_enable']) {
            $fs->remove($this->varDir . '/maintain');
            return true;
        }

        unset($options['maintain_enable']);
        $fs->dumpFile($this->varDir . '/maintain', json_encode($options));

        return true;
    }

    public function removeOptions($group)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Options');

        foreach ($this->getGroupOptionKeys($group) as $name) {
            $one = $repo->findOneBy(['name' => $name]);

            $this->em->remove($one);
        }

        $this->em->flush();

        return true;
    }
}
