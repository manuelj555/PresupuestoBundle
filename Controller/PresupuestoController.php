<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PresupuestoController extends Controller
{

    public function listadoAction()
    {
        $presupuestos = $this->getDoctrine()->getManager()
                ->getRepository("PresupuestoBundle:Presupuestos")
                ->findAll();

        return $this->render("PresupuestoBundle:Presupuesto:listado.html.twig", array(
                    'presupuestos' => $presupuestos,
                ));
    }

}