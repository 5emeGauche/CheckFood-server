<?php

namespace Checkfood\CentralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *  Central DefaultController
 * 
 *  @author Safwen Toukabri <safwen.toukabri@proxym-it.com>
 */
class DefaultController extends Controller {

    private $request;
    private $session;

    public function preExecute() {
        $this->request = $this->getRequest();
        $this->session = $this->request->getSession();
    }

    /**
     * Sigin
     */
    public function indexAction() {
        $formBuilder = $this->createFormBuilder();
        $formBuilder
                ->add('login', 'text')
                ->add('password', 'password');
        $form = $formBuilder->getForm();

        if ($this->request->getMethod() == 'POST') {
            $admin = $this->container->getParameter('admin');
            $post = $this->request->request->get('form');
            if ($post['login'] == $admin['login'] && $post['password'] == $admin['password']) {
                $this->session->set('admin', $admin);
                return new RedirectResponse($this->generateUrl('checkfood_products'));
            }
        }

        if ($this->session->get('admin')) {
            return new RedirectResponse($this->generateUrl('checkfood_products'));
        } else {
            return $this->render('CheckfoodCentralBundle:Default:index.html.twig', array('form' => $form->createView()));
        }
    }

    /**
     * Sigout
     */
    public function signoutAction() {
        $this->session->remove('admin');
        return $this->indexAction();
    }

}
