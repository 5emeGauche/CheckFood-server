<?php

namespace Checkfood\ProductsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Checkfood\ProductsBundle\Entity\Products;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    private $em;
    private $request;
    private $session;
    private $service;

    public function preExecute() {
        $this->em = $this->getDoctrine()->getManager();
        $this->request = $this->getRequest();
        $this->service = $this->get('checkfood_central.central');
        $this->session = $this->request->getSession();
    }

    public function indexAction() {
        $this->service->forbidden($this->session->get('admin'), $this->generateUrl('checkfood_central_homepage'));
        $formErors = array();

        $form = $this->createFormBuilder()
                ->add('import', 'file', array('label' => false, 'attr' => array('title' => 'Importer un fichier csv')))
                ->getForm();

        if ($this->request->getMethod('post') == 'POST') {

            $form->bindRequest($this->request);
            if ($form->isValid()) {

                $file = $form->get('import')->getData();
                $ext = substr(strrchr($file->getClientOriginalName(), '.'), 1);

                if ($ext == 'csv') {

                    $csv = $this->get('checkfood_parse_csv');
                    $csv->delimiter = "\t";
                    $fileContents = file_get_contents($file->getRealpath(), true);
                    $fileContents = $csv->parse_string($fileContents);
                    $itt = 0;
                    $batchSize = 20;
                    foreach ($fileContents as $key => $fileProduct) {
                        $itt++;
                        try {
                            $product = $this->em->getRepository('CheckfoodProductsBundle:Products')->findOneBy(array('code' => $fileProduct['code']));
                            if (!isset($product)) {
                                $product = new Products();
                            }
                            $product->setCode(isset($fileProduct['code']) ? ($fileProduct['code']) : '');
                            $product->setName(isset($fileProduct['product_name']) ? ($fileProduct['product_name']) : '');
                            $product->setBrand(isset($fileProduct['brands']) ? ($fileProduct['brands']) : '');
                            $product->setImageUrl(isset($fileProduct['image_url']) ? ($fileProduct['image_url']) : '');
                            $product->setImageSmallUrl(isset($fileProduct['image_small_url']) ? ($fileProduct['image_small_url']) : '');

                            $this->em->persist($product);
                            if (($itt % $batchSize) == 0) {
                                $this->em->flush();
                                $this->em->clear();
                            }
                        } catch (\Exception $exc) {
                            echo $exc->getTraceAsString();
                        }
                    }
                    try {
                        $this->em->flush();
                        $this->em->clear();
                    } catch (\Exception $exc) {
                        echo $exc->getTraceAsString();
                    }
                } else {
                    $formErors[] = 'Veuillez entrer un fichier au format CSV';
                }
            }
        }

        $params = $this->container->getParameter('params');
        $items_per_page = $params['items_per_page'];
        $page = $this->request->query->get('page', 1);
        $path = $this->generateUrl('checkfood_products');

        $total = count($this->em->getRepository('CheckfoodProductsBundle:Products')->findAll());
        $products = $this->em->getRepository('CheckfoodProductsBundle:Products')->findBy(array(), null, $items_per_page, ($page - 1) * $items_per_page);
        $pagination = $this->service->getPaginationBar($path, $total, $items_per_page, $page);

        return $this->render('CheckfoodProductsBundle:Default:index.html.twig', array('products' => $products, 'pagination' => $pagination, 'form' => $form->createView(), 'msg_error' => $form->getErrors(), 'formErors' => $formErors));
    }
    
    public function deleteAction($id) {
        $errors = array('is_valid' => true);
        $entity = $this->em->getRepository('CheckfoodProductsBundle:Products')->findOneBy(array('id' => $id));
        if (!$entity) {
            $errors['is_valid'] = false;
            $errors['errors'][] = 'Aucun produit n\'a été trouvé.';
        } else {
            try {
                $this->em->remove($entity);
                $this->em->flush();
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errors['is_valid'] = false;
                $errors['errors'][] = 'Erreur lors de supression de produit.';
            }
        }

        return new Response(json_encode($errors), 200, array('content-type' => 'application/json'));
    }

    public function deleteAllAction() {

        try {
            $this->em->getRepository('CheckfoodProductsBundle:Products')->DeleteAll();
            $this->get('session')->getFlashBag()->add(
                    'alert-success', "L'opération de supression a été réalisé avec succès"
            );
            return $this->redirect($this->generateUrl('checkfood_products'));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->getFlashBag()->add(
                    'alert-error', "Une erreur est survenue. Veuillez réessayer ultérieurement."
            );
            return $this->redirect($this->generateUrl('checkfood_products'));
        }

        die;
    }

    public function getProductAction($code) {
        $product = $this->em->getRepository('CheckfoodProductsBundle:Products')->findOneBy(array('code' => $code));
        if (!isset($product)) {
            $errors['codeRetour'] = 0;
            $errors['erreurs'] = 'Produit introuvable';
            $result = $this->service->setWsResult($errors);
        } else {
            $result = $this->service->setWsResult($product);
        }
        return $result;
    }

}
