<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
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
     * @Route("/my-notices", name="my_notices")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myNoticesAction()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return new JsonResponse('user not login');
        }

        $unreadNotices = $this->getDoctrine()->getRepository('YesknMainBundle:Notice')
            ->findBy(['pushTo' => $user, 'isRead' => false], ['createdAt' => 'DESC']);

        $readNotices = $this->getDoctrine()->getRepository('YesknMainBundle:Notice')
            ->findBy(['pushTo' => $user, 'isRead' => true], ['createdAt' => 'DESC']);

        return $this->render('@YesknMain/user/notices.html.twig', [
            'readNotices' => $readNotices,
            'unreadNotices' => $unreadNotices
        ]);
    }

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