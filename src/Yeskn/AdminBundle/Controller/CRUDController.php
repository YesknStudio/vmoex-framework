<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 16:36:37
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\AdminBundle\CrudEvent\AbstractCrudEntityEvent;
use Yeskn\AdminBundle\CrudEvent\AbstractCrudListEvent;
use Yeskn\AdminBundle\CrudEvent\CrudEventInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\Support\Http\ApiOk;
use Yeskn\Support\Http\Session\Flash;

class CRUDController extends Controller
{
    use Flash;

    /**
     * @Route("/{entity}/list", methods={"GET"}, name="admin_list")
     *
     * @param $entity
     * @param $request
     *
     * @return Response
     */
    public function listAction($entity, Request $request)
    {
        $pageLimit = $request->query->get('pageLimit', 20);
        $currentPage = $request->query->get('currentPage', 1);

        $entity = ucfirst($entity);
        $repo = $this->getDoctrine()->getRepository('YesknMainBundle:' . $entity);

        $list = $repo->findBy([], ['id' => 'DESC'], $pageLimit, ($currentPage - 1) * $pageLimit);
        $total = $repo->total();

        $typeClass = "Yeskn\MainBundle\Form\\{$entity}Type";
        $entityClass = "Yeskn\MainBundle\Entity\\{$entity}";

        $data = $this->startEntitiesRenderEvent($entity, $list);

        return $this->render('@YesknAdmin/crud/list.html.twig', [
            'entity' => lcfirst($entity),
            'entitySubTitle' => empty($data['entitySubTitle']) ? '' : $data['entitySubTitle'],
            'columns' => $data['columns'],
            'column_width' => empty($data['column_width']) ? [] : $data['column_width'],
            'create_btn' => empty($data['create_btn']) ? '' : $data['create_btn'],
            'edit_btn' => empty($data['edit_btn']) ? '' : $data['edit_btn'],
            'list' => $data['list'],
            'ids' => $data['ids'],
            'entityName' => $entityClass::NAME,
            'form' => $this->createForm($typeClass, new $entityClass)->createView(),
            'extra' => empty($data['extra']) ? [] : $data['extra'],
            'allPage' => ceil($total / $pageLimit),
            'pageLimit' => $pageLimit
        ]);
    }

    /**
     * @Route("/delete_{entity}_{id}", methods={"POST"}, requirements={"id":"\d+"}, name="admin_delete")
     *
     * @param $entity
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($entity, $id)
    {
        $entity = ucfirst($entity);
        $entityObj = $this->getDoctrine()->getRepository('YesknMainBundle:'. $entity)
            ->find($id);

        if ($entityObj) {
            try {
                $this->processEntityDeleteEvent($entity, $entityObj);

                $em = $this->get('doctrine.orm.entity_manager');

                $em->remove($entityObj);
                $em->flush();
            } catch (\Exception $exception) {
                return new JsonResponse(['status' => 0, 'message' => $exception->getMessage()]);
            }

            $this->addSuccessFlash();

            return new JsonResponse(['status' => 1, 'message' => '删除成功']);
        }

        return new JsonResponse(['status' => 0, 'message' => '数据不存在或者已经删除']);
    }

    /**
     * @Route("/edit_{entity}", methods={"POST", "GET"}, requirements={"id":"\d+"}, name="admin_edit")
     *
     * @param $request
     * @param $entity
     *
     * @return JsonResponse|Response
     */
    public function editAction(Request $request, $entity)
    {
        $entity = ucfirst($entity);
        $entityClass = "Yeskn\\MainBundle\\Entity\\" . $entity;

        $repo = $this->getDoctrine()->getRepository('YesknMainBundle:' . $entity);

        if ($id = $request->get('id')) {
            $entityObj = $repo->find($id);

            $this->startEntityEditEvent($entity, $entityObj);

        } else {
            $entityObj = new $entityClass;
        }

        $form = $this->createForm("Yeskn\MainBundle\Form\\{$entity}Type", $entityObj);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $this->processEntityEditEvent($entity, $entityObj);

            $em->persist($entityObj);
            $em->flush();

            $this->finishEntityEditEvent($entity, $entityObj);

            $this->addSuccessFlash();

            return new ApiOk();
        }

        return $this->render('@YesknAdmin/modals/entity-modal.html.twig', [
            'form' => $form->createView(),
            'title' => '编辑' . $entityClass::NAME,
            'action' => $this->generateUrl('admin_edit', [
                'entity' => $entity,
                'id' => $id
            ]),
            'formId' => $request->get('r')
        ]);
    }

    protected function startEntityEditEvent($entityName, $entityObj)
    {
        if ($entityName == 'User') {
            $this->checkUserEdition($entityObj);
        }

        $entity = ucfirst($entityName);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\StartEdit{$entity}Event";

        /** @var AbstractCrudEntityEvent $processor */
        if (class_exists($processorClass)) {
            $processor = $this->get($processorClass)->setEntity($entityObj);
        } else {
            return true;
        }

        return $processor->execute();
    }

    protected function startEntitiesRenderEvent($entity, array $list)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\StartRender{$entity}ListEvent";

        /** @var AbstractCrudListEvent $processor */
        if (class_exists($processorClass)) {
            $processor = $this->get($processorClass)->setList($list);
        } else {
            return true;
        }

        return $processor->execute();
    }

    protected function processEntityEditEvent($entity, $entityObj)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\ProcessEdit{$entity}Event";

        /** @var AbstractCrudEntityEvent $processor */
        if (class_exists($processorClass)) {
            $processor = $this->get($processorClass)->setEntity($entityObj);
        } else {
            return true;
        }

        return $processor->execute();
    }

    protected function finishEntityEditEvent($entity, $entityObj)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\FinishEdit{$entity}Event";

        /** @var CrudEventInterface $processor */
        if (class_exists($processorClass)) {
            $processor = $this->get($processorClass)->setEntity($entityObj);
        } else {
            return true;
        }

        return $processor->execute();
    }

    protected function processEntityDeleteEvent($entity, $entityObj)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\ProcessDelete{$entity}Event";

        /** @var AbstractCrudEntityEvent $processor */
        if (class_exists($processorClass)) {
            $processor = $this->get($processorClass)->setEntity($entityObj);
        } else {
            return true;
        }

        return $processor->execute();
    }

    protected function checkUserEdition(User $user)
    {
        if ($user->getId() == $this->getUser()->getId()) {
            throw new NotAcceptableHttpException($this->get('translator')->trans('cant_modify_current_user_in_admin'));
        }
    }
}
