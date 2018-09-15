<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 16:36:37
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CRUDController extends Controller
{
    /**
     * @Route("/delete/{entity}/{id}", methods={"POST"}, requirements={"id":"\d+"}, name="admin_delete")
     *
     * @param $entity
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($entity, $id)
    {
        $entity = $this->getDoctrine()->getRepository('YesknMainBundle:'. ucfirst($entity))
            ->find($id);

        if ($entity) {
            $em = $this->get('doctrine.orm.entity_manager');

            try {
                $em->remove($entity);
                $em->flush();
            } catch (\Exception $exception) {
                return new JsonResponse(['status' => 0, 'message' => $exception->getMessage()]);
            }

            return new JsonResponse(['status' => 1, 'message' => '删除成功']);
        }

        return new JsonResponse(['status' => 0, 'message' => '数据不存在或者已经删除']);
    }
}