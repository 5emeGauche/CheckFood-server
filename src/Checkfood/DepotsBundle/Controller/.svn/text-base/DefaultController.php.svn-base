<?php

namespace Checkfood\DepotsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Checkfood\DepotsBundle\Entity\Depots;
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
                    foreach ($fileContents as $key => $fileDepot) {
                        $itt++;
                        try {
                            $depot = $this->em->getRepository('CheckfoodDepotsBundle:Depots')->findOneBy(array('longitude' => $fileDepot['Longitude'], 'latitude' => $fileDepot['Latitude']));
                            if (!isset($depot)) {
                                $depot = new Depots();
                            }
                            $depot->setName(isset($fileDepot['Nom']) ? utf8_encode($fileDepot['Nom']) : '');
                            $depot->setAddress(isset($fileDepot['Adresse']) ? utf8_encode($fileDepot['Adresse']) : '');
                            $depot->setLongitude(isset($fileDepot['Longitude']) ? utf8_encode($fileDepot['Longitude']) : '');
                            $depot->setLatitude(isset($fileDepot['Latitude']) ? utf8_encode($fileDepot['Latitude']) : '');
                            try {
                                $depot->setOpeningTime(isset($fileDepot["Horaire d'ouverture"]) ? new \DateTime(utf8_encode($fileDepot["Horaire d'ouverture"])) : '');
                            } catch (\Exception $exc) {
                                
                            }

                            try {
                                $depot->setClosingTime(isset($fileDepot["Horaire de Fermiture"]) ? new \DateTime(utf8_encode($fileDepot["Horaire de Fermiture"])) : '');
                            } catch (\Exception $exc) {
                                
                            }

                            $this->em->persist($depot);
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
        $path = $this->generateUrl('checkfood_depots');

        $total = count($this->em->getRepository('CheckfoodDepotsBundle:Depots')->findAll());
        $depots = $this->em->getRepository('CheckfoodDepotsBundle:Depots')->findBy(array(), null, $items_per_page, ($page - 1) * $items_per_page);
        $pagination = $this->service->getPaginationBar($path, $total, $items_per_page, $page);

        return $this->render('CheckfoodDepotsBundle:Default:index.html.twig', array('depots' => $depots, 'pagination' => $pagination, 'form' => $form->createView(), 'msg_error' => $form->getErrors(), 'formErors' => $formErors));
    }

    public function deleteAction($id) {
        $errors = array('is_valid' => true);
        $entity = $this->em->getRepository('CheckfoodDepotsBundle:Depots')->findOneBy(array('id' => $id));
        if (!$entity) {
            $errors['is_valid'] = false;
            $errors['errors'][] = 'Aucun dépot n\'a été trouvé.';
        } else {
            try {
                $this->em->remove($entity);
                $this->em->flush();
            } catch (\Doctrine\DBAL\DBALException $e) {
                $errors['is_valid'] = false;
                $errors['errors'][] = 'Erreur lors de supression de dépot.';
            }
        }

        return new Response(json_encode($errors), 200, array('content-type' => 'application/json'));
    }

    public function deleteAllAction() {

        try {
            $this->em->getRepository('CheckfoodDepotsBundle:Depots')->DeleteAll();
            $this->get('session')->getFlashBag()->add(
                    'alert-success', "L'opération de supression a été réalisé avec succès"
            );
            return $this->redirect($this->generateUrl('checkfood_depots'));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->getFlashBag()->add(
                    'alert-error', "Une erreur est survenue. Veuillez réessayer ultérieurement."
            );
            return $this->redirect($this->generateUrl('checkfood_depots'));
        }

        die;
    }

    public function getDepotsAction() {
        $depots = $this->em->getRepository('CheckfoodDepotsBundle:Depots')->findAll();
        if (!isset($depots)) {
            $errors['codeRetour'] = 0;
            $errors['erreurs'] = "Aucun point de dépot n'a été trouvé";
            $result = $this->service->setWsResult($errors);
        } else {
            $result = $this->service->setWsResult($depots);
        }
        return $result;
    }

}
