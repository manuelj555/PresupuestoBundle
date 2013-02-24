<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Form\PresupuestoForm;
use K2\PresupuestoBundle\Response\SuccessResponse;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Util;

class PresupuestoController extends Controller
{

    public function listadoAction($page)
    {
        $query = $this->getDoctrine()->getEntityManager()
                ->createQuery("SELECT p FROM PresupuestoBundle:Presupuestos p");

        $presupuestos = $this->get("knp_paginator")->paginate($query, $page);

        return $this->render("PresupuestoBundle:Presupuesto:listado.html.twig", array(
                    'presupuestos' => $presupuestos,
                ));
    }

    public function edicionAction($id)
    {
        $presupuesto = $this->getPresupuesto($id);

        $form = $this->createForm(new PresupuestoForm(), $presupuesto);

        if ($this->getRequest()->isMethod('POST')) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $presupuesto->guardar($em);
                $em->flush();
                return new SuccessResponse("Presupuesto Guardado");
            } else {
                return new ErrorResponse($form->getErrors());
            }
        }

        return $this->render("PresupuestoBundle:Presupuesto:presupuesto.html.twig"
                        , array(
                    'form' => $form->createView(),
                    'presupuesto' => $presupuesto,
                ));
    }

    /**
     * 
     * @param int $id
     * @return Presupuestos 
     */
    protected function getPresupuesto($id)
    {
        if (null !== $id) {

            $presupuesto = $this->getDoctrine()
                    ->getRepository("PresupuestoBundle:Presupuestos")
                    ->find($id);

            if (!$presupuesto) {
                throw $this->createNotFoundException("No existe el presupuesto $id");
            }
        } else {
            $presupuesto = new Presupuestos();
        }

        return $presupuesto;
    }

}