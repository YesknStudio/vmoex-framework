<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-06-03 21:14:28
 */

namespace Yeskn\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeskn\BlogBundle\Entity\Tab;
use Yeskn\BlogBundle\Form\TabType;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class TabController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/admin/tab")
 */
class TabController extends Controller
{
    /**
     * @Route("/index", name="admin_tab_index")
     */
    public function indexAction(Request $request)
    {
        $qb = $this->container->get('doctrine')->getRepository('YesknBlogBundle:Tab');

        if ($request->get('level')) {
            $tabs = $qb->findBy(['level' => $request->get('level')]);
        } else {
            $tabs = $qb->findAll();
        }

        return $this->render('@YesknAdmin/Tab/index.html.twig',array(
            'tabs' => $tabs
        ));
    }

    /**
     * @inheritdoc
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $tab = new Tab();
        $form = $this->createForm(TabType::class, $tab);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $avatar
             */
            $avatar = $request->files->get('avatar');
            if ($avatar) {
                $extension = $avatar->guessExtension();
                $fileName = $tab->getAlias() . mt_rand(1000, 9999) . '.' . $extension;
                $targetPath = $this->getParameter('kernel.project_dir') . '/web/tavatar/' . $fileName;

                $fs = new Filesystem();
                $fs->copy($avatar->getRealPath(), $targetPath);

                Image::configure(array('driver' => 'gd'));

                $image = Image::make($targetPath);
                $image->resize(100, 100)->save();

                $tab->setAvatar('tavatar/'.$fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($tab);
            $em->flush();
            $this->addFlash('success','创建板块成功');
            return $this->redirectToRoute('admin_tab_index');
        }
        return $this->render('@YesknAdmin/Tab/add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/edit")
     * @return Response
     */
    public function editAction(Request $request)
    {
        $tabAlias = $request->get('tab');
        $tab = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findOneBy(['alias' => $tabAlias]);

        $form = $this->createForm(TabType::class, $tab);
        $avatar = $tab->getAvatar();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if (empty($tab->getAvatar())) {
                $tab->setAvatar($avatar);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tab);
            $em->flush();
            $this->addFlash('success','修改板块成功');
            return $this->redirectToRoute('admin_tab_index');
        }
        return $this->render('@YesknAdmin/Tab/add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @Route("/delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $tabAlias = $request->get('tab');
        $tab = $this->getDoctrine()->getRepository('YesknBlogBundle:Tab')
            ->findOneBy(['alias' => $tabAlias]);

        $em = $this->getDoctrine()->getManager();

        $em->remove($tab);

        $em->flush();

        $this->addFlash('success','删除板块成功');
        return $this->redirectToRoute('admin_tab_index');
    }
}