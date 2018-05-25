<?php

namespace Yeskn\UserBundle\Controller;

use Yeskn\BlogBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route("/login" , name="yeskn_user_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('YesknUserBundle:Security:login.html.twig', array(
            // ...
            'last_username' => $lastUsername,
            'error'         => $error
        ));
    }

    /**
     * @Route("/loginCheck" , name="yeskn_login_check")
     * @throws \Exception
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/logout" ,name="yeskn_user_logout")
     * @throws
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/register", name="user_registration")
     * @param $request Request
     * @return RedirectResponse
     */
    public function reg2Action(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRegisterAt(new \DateTime());
            $user->setLoginAt(new \DateTime());
            $user->setType('user');
            $user->setApiKey(md5(uniqid()));
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('yeskn_user_login');
        }

        return $this->render(
            '@YesknUser/Security/register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function verifyEmailAction()
    {
        return $this->render('YesknUserBundle:Security:verify_email.html.twig', array(
            // ...
        ));
    }

    public function forgotAction()
    {
        return $this->render('YesknUserBundle:Security:forgot.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/encode")
     */
    public function encode()
    {

        $repo = $this->getDoctrine()->getRepository('YesknBlogBundle:User');
        $user = $repo->find(3);
        $plainPassword = '123456';
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, $plainPassword);
        dump($encoded);die;
    }

}
