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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\AdminBundle\CrudEvent\CrudEventInterface;
use Yeskn\AdminBundle\Services\LoadTranslationService;
use Yeskn\Support\Http\ApiOk;
use Yeskn\Support\Http\Session\Flash;

class CRUDController extends Controller
{
    use Flash;

    protected $startEntityEditParam;

    protected $processEntityEditParam;

    /**
     * @Route("/delete_{entity}_{id}", methods={"POST"}, requirements={"id":"\d+"}, name="admin_delete")
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
        $repo = $this->getDoctrine()->getRepository('YesknMainBundle:' . $entity);

        if ($id = $request->get('id')) {
            $entityObj = $repo->find($id);

            $this->startEntityEditEvent($entity, $entityObj);

        } else {
            $entityClass = "Yeskn\\MainBundle\\Entity\\" . $entity;
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
            'title' => '编辑板块',
            'action' => $this->generateUrl('admin_edit', [
                'entity' => $entity,
                'id' => $id
            ]),
            'formId' => $request->get('r')
        ]);
    }

    protected function startEntityEditEvent($entityName, $entityObj)
    {
        $entity = ucfirst($entityName);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\StartEdit{$entity}Event";

        switch ($entity) {
            case 'Tab':
                /** @var CrudEventInterface $processor */
                $processor = new $processorClass($entityObj);
                break;
            default:
                if (class_exists($processorClass)) {
                    $processor = new $processorClass($entityObj);
                } else {
                    return true;
                }
        }

        return $this->startEntityEditParam = $processor->execute();
    }

    protected function processEntityEditEvent($entity, $entityObj)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\ProcessEdit{$entity}Event";

        /** @var CrudEventInterface $processor */
        switch ($entity) {
            case 'Tab':
                $processor = new $processorClass(
                    $entityObj,
                    $this->getParameter('kernel.project_dir'),
                    $this->startEntityEditParam['oldAvatar']
                );
                break;
            case 'User':
                $processor = new $processorClass(
                    $entityObj,
                    $this->get('security.password_encoder'),
                    $this->startEntityEditParam,
                    $this->getParameter('kernel.project_dir')
                );
                break;
            case 'Tag':
                $processor = new $processorClass($entityObj);
                break;
            default:
                if (class_exists($processorClass)) {
                    $processor = new $processorClass($entityObj);
                } else {
                    return true;
                }
        }

        return $this->processEntityEditParam = $processor->execute();
    }

    protected function finishEntityEditEvent($entity, $entityObj)
    {
        $entity = ucfirst($entity);
        $processorClass = "Yeskn\\AdminBundle\\CrudEvent\\FinishEdit{$entity}Event";

        switch ($entity) {
            case 'Translation':
                /** @var CrudEventInterface $processor */
                $processor = new $processorClass($entityObj, $this->get(LoadTranslationService::class));
                break;
            default:
                if (class_exists($processorClass)) {
                    $processor = new $processorClass($entityObj);
                } else {
                    return true;
                }
        }

        return $processor->execute();
    }
}