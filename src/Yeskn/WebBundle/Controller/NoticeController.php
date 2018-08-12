<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 12:59:41
 */

namespace Yeskn\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Yeskn\WebBundle\Entity\Notice;

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

        $unreadNotices = $this->getDoctrine()->getRepository('YesknWebBundle:Notice')
            ->findBy(['pushTo' => $user, 'isRead' => false], ['createdAt' => 'DESC']);

        $readNotices = $this->getDoctrine()->getRepository('YesknWebBundle:Notice')
            ->findBy(['pushTo' => $user, 'isRead' => true], ['createdAt' => 'DESC']);

        return $this->render('@YesknWeb/notices.html.twig', [
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
        $notices = $this->getDoctrine()->getRepository('YesknWebBundle:Notice')
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