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
use Yeskn\MainBundle\Entity\Options;
use Yeskn\MainBundle\Form\Entity\BasicManage;

class ManageBasicLogic
{
    /**
     * @var EntityManager
     */
    private $em;

    private $options;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        $this->options = [
            'siteLogo',
            'siteSince',
            'siteVersion',
            'siteAnnounce'
        ];
    }

    /**
     * @return BasicManage
     */
    public function getBasicManage()
    {
        $basicMange = new BasicManage();

        $repo = $this->em->getRepository('YesknMainBundle:Options');

        foreach ($this->options as $option) {
            $entity = $repo->findOneBy(['name' => $option]);

            if ($option == 'siteSince') {
                $basicMange->set($option, new \DateTime($entity->getValue()));
                continue;
            }

            if ($option == 'siteAnnounce') {
                $basicMange->set($option, boolval($entity->getValue()));
                continue;
            }

            if ($entity) {
                $basicMange->set($option, $entity->getValue());
            }
        }

        return $basicMange;
    }

    public function setBasicManage(BasicManage $basicManage)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Options');

        foreach ($this->options as $option) {
            $entity = $repo->findOneBy(['name' => $option]);

            if (empty($entity)) {
                $entity = new Options();
            }

            $entity->setName($option);

            $entity->setValue($basicManage->get($option));

            if ($option == 'siteAnnounce') {
                $entity->setValue(intval($basicManage->getSiteAnnounce()));
            }

            if ($option == 'siteSince') {
                $entity->setValue($basicManage->getSiteSince()->format('Y-m-d'));
            }

            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }
}
