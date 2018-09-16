<?php

namespace Yeskn\AdminBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\MainBundle\Entity\Post;

/**
 * Class PostController
 * @Route("/post")
 * @Security("has_role('ROLE_ADMIN')")
 */
class PostController extends Controller
{
    /**
     * @Route("/create", methods={"GET", "POST"})
     * @param $request Request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm('Yeskn\MainBundle\Form\PostType', $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());
            $post->setIsDeleted(false);
            $post->setStatus('published');
            $post->setSummary('');

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', '创建文章成功');
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@YesknAdmin/Post/create.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit")
     *
     * @param $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $post = $this->getDoctrine()->getRepository('YesknMainBundle:Post')
            ->find($request->get('id'));

        $form = $this->createForm('Yeskn\MainBundle\Form\PostType', $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setUpdatedAt(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success', '编辑文章成功');
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('@YesknAdmin/post/create.html.twig', [
            'title' => '编辑文章',
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}")
     * @inheritdoc
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->find($id);
        if ($post) {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success','delete success');
            return $this->redirectToRoute('admin_post_index');
        } else {
            $this->addFlash('error','no post found for id' . $id);
            return $this->redirectToRoute('admin_post_index');
        }
    }

    /**
     * @Route("/preview")
     */
    public function previewAction()
    {

    }

    /**
     * @Route("/", name="admin_post_index")
     */
    public function listAction()
    {
        /**
         * @var Post[] $posts
         */
        $posts = $this->getDoctrine()->getRepository('YesknMainBundle:Post')->findBy([], ['updatedAt' => 'DESC']);

        return $this->render('@YesknAdmin/post/index.html.twig', array(
            'list' => $posts
        ));
    }
}