<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Response\SuccessResponse;

class ManoDeObraController extends Controller
{

    public function listadoAction($page)
    {
        $query = $this->getDoctrine()->getEntityManager()
                ->createQuery("SELECT mdo,tip,med 
                               FROM PresupuestoBundle:ManosDeObra mdo
                               JOIN mdo.medidas med
                               JOIN mdo.tiposDeObras tip");

        $registros = $this->get("knp_paginator")->paginate($query, $page);

        return $this->render("PresupuestoBundle:ManoDeObra:listado.html.twig"
                        , array(
                    'manosdeobra' => $registros,
                ));
    }

    public function agregarAction()
    {
        $manoDeObra = new ManosDeObra();

        $form = $this->createForm(new ManoDeObraForm(), $manoDeObra);

        if ($this->getRequest()->isMethod('POST')) {
            var_dump($this->getRequest()->getContent(), true);
            $form->bind(json_decode($this->getRequest()->get($form->getName()), true));
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

}