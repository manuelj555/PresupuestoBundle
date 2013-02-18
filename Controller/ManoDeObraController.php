<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($manoDeObra);
                $em->flush();
            }else{
                return new Response($form->getErrorsAsString());
                return new JsonResponse($form->getErrors());
            }
                return new Response($form->getErrorsAsString());
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