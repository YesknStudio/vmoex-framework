<?php

namespace Yeskn\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\Post;
use Yeskn\BlogBundle\Entity\Tag;

/**
 * Class PostController
 * @Route("/admin/post")
 * @Security("has_role('ROLE_ADMIN')")
 * @package Yeskn\AdminBundle\Controller
 */
class PostController extends AdminCommonController
{
    /**
     * @Route("/create")
     * @param $request Request
     * @Method({"GET","POST"})
     * @return mixed
     */
    public function createAction(Request $request)
    {
        $post = new Post();

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm('Yeskn\BlogBundle\Form\PostType', $post)
            ->add('saveCraft', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $post->setCreatedAt(new \DateTime());
            $post->setIsDeleted(false);
            $post->setAuthor($this->getUser());
            $post->setStatus('published');

            $post->addCategory($this->getDoctrine()->getRepository('YesknBlogBundle:Category')
                ->find($request->get('select-category'))
            );

            $requestTags = trim($request->get('input-tag'));
            if ($requestTags) {
                $_tags = explode(',', $requestTags);
                foreach ($_tags as $_tag) {
                    if ($currentTag = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')
                        ->findOneBy(array('name' => $_tag))
                    ) {
                        $post->addTag($currentTag);
                    } else {
                        $currentTag = new Tag();
                        $currentTag->setName($_tag);
                        $currentTag->setStatus(1);
                        $currentTag->setSlug(uniqid());
                        $currentTag->setCreatedAt(new \DateTime());
                        $post->addTag($currentTag);
                        $entityManager->persist($currentTag);
                    }
                }
            }
            $entityManager->persist($post);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');
            return $this->redirectToRoute('yeskn_admin_post_list');
        }
        $categories = $this->getDoctrine()->getRepository('YesknBlogBundle:Category')->findAll();
        $tags = $this->getDoctrine()->getRepository('YesknBlogBundle:Tag')->findBy(
            array(),
            array('id' => 'DESC'),
            5
        );
        return $this->render('@YesknAdmin/Post/create2.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'categories' => $categories,
            'tags' => $tags
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {

    }

    /**
     * @Route("/delete/{id}")
     * @inheritdoc
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->find($id);
        if ($post) {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success','delete success');
            return $this->redirectToRoute('yeskn_admin_post_list');
        } else {
            $this->addFlash('error','no post found for id' . $id);
            return $this->redirectToRoute('yeskn_admin_post_list');
        }
    }

    /**
     * @Route("/preview")
     */
    public function previewAction()
    {

    }

    /**
     * @Route("/list")
     * @Route("/index")
     * @Route("/")
     */
    public function listAction()
    {
        $posts = $this->getDoctrine()->getRepository('YesknBlogBundle:Post')->findAll();
        return $this->render('@YesknAdmin/Post/index.html.twig', array(
            'posts' => $posts
        ));
    }
}