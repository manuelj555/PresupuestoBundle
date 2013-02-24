<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Response\SuccessResponse;

class ManoDeObraController extends Controller
{

    public function listadoAction($page, $description)
    {
        if ($this->getRequest()->isMethod('POST')) {
            $description = $this->getRequest()->request->get("description");
            return $this->redirect($this->generateUrl('manosdeobra_listado'
                                    , array('description' => $description)));
        }
        $query = $this->getDoctrine()->getEntityManager()
                ->createQuery("SELECT mdo,tip,med 
                               FROM PresupuestoBundle:ManosDeObra mdo
                               JOIN mdo.medidas med
                               JOIN mdo.tiposDeObras tip
                               WHERE mdo.descripcion LIKE :description
                               ORDER BY mdo.descripcion")
                ->setParameter('description', "%$description%");

        $registros = $this->get("knp_paginator")->paginate($query, $page);

        if ($page > 1 and $registros->count() == 0) {
            throw $this->createNotFoundException("No existe la pagina $page en los resultados de la consulta de las manos de obra");
        }

        return $this->render("PresupuestoBundle:ManoDeObra:listado.html.twig"
                        , array(
                    'manosdeobra' => $registros,
                    'description' => $description,
                ));
    }

    public function agregarAction()
    {
        $manoDeObra = new ManosDeObra();

        $form = $this->createForm(new ManoDeObraForm(), $manoDeObra);

        if ($this->getRequest()->isMethod('POST')) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($manoDeObra);
                $em->flush();
                return new SuccessResponse("La Mano de Obra se Guardó con exito");
            } else {
                var_dump($form->hasErrors(), $form->getErrors(), $form->getErrorsAsString());
                return new ErrorResponse($form->getErrors(), ErrorResponse::ALERT_FORM);
            }
        }

        return $this->agregarResponse($form);
    }

    public function editarAction($id)
    {
        $manoDeObra = $this->getEntity($id);

        $form = $this->createForm(new ManoDeObraForm(), $manoDeObra);

        if ($this->getRequest()->isMethod('POST')) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($manoDeObra);
                $em->flush();
                return new SuccessResponse("La Mano de Obra se Guardó con exito");
            } else {
                var_dump($form->hasErrors(), $form->getErrors(), $form->getErrorsAsString());
                return new ErrorResponse($form->getErrors(), ErrorResponse::ALERT_FORM);
            }
        }

        return $this->agregarResponse($form);
    }

    public function agregarResponse($form)
    {
        $view = $this->get('k2_view_selector')
                ->select("PresupuestoBundle:ManoDeObra:agregar");

        return $this->render($view, array(
                    'form' => $form->createView(),
                ));
    }

    public function getAllAction()
    {
        $result = $this->getDoctrine()->getEntityManager()
                ->createQuery("SELECT mdo,tip,med 
                               FROM PresupuestoBundle:ManosDeObra mdo
                               JOIN mdo.medidas med
                               JOIN mdo.tiposDeObras tip
                               ORDER BY mdo.descripcion")
                ->getResult();

        return $this->render("PresupuestoBundle:ManoDeObra:all.ajax.html.twig"
                        , array(
                    'manosdeobra' => $result,
                ));
    }

    /**
     * 
     * @param int $id
     * @return ManosDeObra
     */
    protected function getEntity($id)
    {
        $manoDeObra = $this->getDoctrine()
                ->getRepository("PresupuestoBundle:ManosDeObra")
                ->find($id);

        if (!$manoDeObra) {
            throw $this->createNotFoundException("No existe la Mano de Obra $id");
        }

        return $manoDeObra;
    }

}