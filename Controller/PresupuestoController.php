<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PresupuestoController extends Controller
{

    public function listadoAction()
    {
        $query = $this->getDoctrine()->getEntityManager()
                ->createQuery("SELECT p FROM PresupuestoBundle:Presupuestos p");

        $presupuestos = $this->get("knp_paginator")->paginate($query
                , $this->getRequest()->query->get("page", 1));

        return $this->render("PresupuestoBundle:Presupuesto:listado.html.twig", array(
                    'presupuestos' => $presupuestos,
                ));
    }

}