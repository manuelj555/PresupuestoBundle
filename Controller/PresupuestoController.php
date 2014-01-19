<?php

namespace K2\PresupuestoBundle\Controller;

use Closure;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Form\PresupuestoForm;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Response\RedirectResponse;
use K2\PresupuestoBundle\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresupuestoController extends Controller
{

    public function listadoAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT p FROM PresupuestoBundle:Presupuestos p");

        $presupuestos = $this->get("knp_paginator")->paginate($query, $page);

        return $this->render("PresupuestoBundle:Presupuesto:listado.html.twig", array(
                    'presupuestos' => $presupuestos,
        ));
    }

    /**
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     */
    public function edicionAction(Presupuestos $presupuesto)
    {
        $form = $this->createForm(new PresupuestoForm(), $presupuesto);

        if ($this->getRequest()->isMethod('POST')) {

            $descripcionesOriginales = $presupuesto->getDescripciones()->toArray();

            $data = json_decode($this->getRequest()->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $presupuesto->guardar($em, $descripcionesOriginales);
                $em->flush();
                if ($id === null) {
                    //si es nuevo redireccionamos a editar
                    return new RedirectResponse($this
                                    ->generateUrl("presupuesto_edicion", array(
                                        'id' => $presupuesto->getId(),
                    )));
                } else {
                    return new SuccessResponse("Presupuesto Guardado");
                }
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
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     */
    public function exportAction(Presupuestos $presupuesto)
    {
        return $this->prepareExport(function()use ($presupuesto) {
                    require "/../Resources/views/Presupuesto/presupuesto.xls.php";
                }, $presupuesto->getTitulo());
    }

    protected function prepareExport(Closure $function, $filename = 'report')
    {
        $response = new StreamedResponse($function);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$filename}.xlsx\"");
        $response->headers->set('Cache-Control', 'ax-age=0');
        return $response;
    }

}
