<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jaggle
 * Create: 2018-09-12 14:30:12
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     */
    public function indexAction()
    {
        $postCount = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->countPost();
        $userCount = $this->getDoctrine()->getRepository('YesknMainBundle:User')->countUser();
        $commentCount = $this->getDoctrine()->getRepository('YesknMainBundle:Comment')->countComment();

        $todayLoginUserCount = $this->getDoctrine()->getRepository('YesknMainBundle:User')
            ->getTodayLoggedUserCount();

        return $this->render('YesknAdminBundle:default:index.html.twig', [
            'count' => [
                'post' => $postCount,
                'user' => $userCount,
                'todayLoginUserCount' => $todayLoginUserCount,
                'comment' => $commentCount
            ]
        ]);
    }

    /**
     * @Route("/confirm_modal", name="admin_confirm_modal", methods={"POST"})
     *
     * @param $request
     * @return Response
     */
    public function confirmModalAction(Request $request)
    {
        return $this->render('@YesknAdmin/modals/confirm-modal.html.twig', [
            'modalId' => $request->get('modalId'),
            'title' => $request->get('title', '提示'),
            'message' => $request->get('message', '你确定要执行该操作吗？')
        ]);
    }

    /**
     * @Route("/alert_modal", name="admin_alert_modal", methods={"GET"})
     *
     * @param $request
     * @return Response
     */
    public function alertModalAction(Request $request)
    {
        return $this->render('@YesknAdmin/modals/alert-modal.html.twig', [
            'modalId' => $request->get('modalId'),
            'title' => $request->get('title', '警告'),
            'message' => $request->get('message', '操作失败！')
        ]);
    }
}
