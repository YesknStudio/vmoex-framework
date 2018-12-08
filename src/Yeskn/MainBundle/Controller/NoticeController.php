<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-14 15:18:54
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Yeskn\MainBundle\Entity\Notice;

class NoticeController extends Controller
{
    /**
     * @Route("/set-notice-red", name="set_notice_red", methods={"POST"})
     */
    public function setNoticeRedAction()
    {
        /**
         * @var Notice[] $notices
         */
        $notices = $this->getDoctrine()->getRepository('YesknMainBundle:Notice')
            ->createQueryBuilder('p')
            ->where('p.pushTo = :user')->setParameter('user', $this->getUser())
            ->andWhere('p.isRead = false')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult(5);

        foreach ($notices as $notice) {
            $notice->setIsRead(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['ret' => 1]);
    }
}
