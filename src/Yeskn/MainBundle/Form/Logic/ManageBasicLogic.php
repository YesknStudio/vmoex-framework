<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 19:11:35
 */

namespace Yeskn\MainBundle\Form\Logic;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Yeskn\MainBundle\Entity\Manage;
use Yeskn\MainBundle\Form\Entity\BasicManage;

class ManageBasicLogic
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return BasicManage
     */
    public function getBasicManage()
    {
        $basicMange = new BasicManage();

        $repo = $this->em->getRepository('YesknMainBundle:Manage');

        $entity = $repo->findOneBy(['name' => 'siteLogo']);

        if ($entity) {
            $basicMange->setSiteLogo($entity->getValue());
        }

        $entity = $repo->findOneBy(['name' => 'siteSince']);

        if ($entity) {
            $basicMange->setSiteSince(new \DateTime($entity->getValue()));
        }

        $entity = $repo->findOneBy(['name' => 'siteVersion']);

        if ($entity) {
            $basicMange->setSiteVersion($entity->getValue());
        }

        return $basicMange;
    }

    public function setBasicManage(BasicManage $basicManage)
    {
        $repo = $this->em->getRepository('YesknMainBundle:Manage');

        if (!empty($basicManage->getSiteLogo())) {

            $entity = $repo->findOneBy(['name' => 'siteLogo']);

            if (empty($entity)) {
                $entity = new Manage();
            }

            $entity->setName('siteLogo');
            $entity->setValue($basicManage->getSiteLogo());

            $this->em->persist($entity);
            $this->em->flush();
        }

        if (!empty($basicManage->getSiteSince())) {
            $entity = $repo->findOneBy(['name' => 'siteSince']);

            if (empty($entity)) {
                $entity = new Manage();
            }

            $entity->setName('siteSince');
            $entity->setValue($basicManage->getSiteSince()->format('Y-m-d'));

            $this->em->persist($entity);
            $this->em->flush();

        }

        if (!empty($basicManage->getSiteVersion())) {
            $entity = $repo->findOneBy(['name' => 'siteVersion']);

            if (empty($entity)) {
                $entity = new Manage();
            }

            $entity->setName('siteVersion');
            $entity->setValue($basicManage->getSiteVersion());

            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }
}