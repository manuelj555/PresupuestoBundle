<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Form\PresupuestoForm;
use K2\PresupuestoBundle\Response\SuccessResponse;
use K2\PresupuestoBundle\Response\ErrorResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use K2\PresupuestoBundle\Response\RedirectResponse;

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
                            ->generateUrl("presupuesto_edicion",array(
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
     * 
     * @param int $id
     * @return Presupuestos 
     */
    protected function getPresupuesto($id)
    {
        if (null !== $id) {

            $presupuesto = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery("SELECT p 
                                   FROM PresupuestoBundle:Presupuestos p
                                   LEFT JOIN p.descripciones des
                                   WHERE p.id = :id")
                    ->setParameter("id", $id, \PDO::PARAM_INT)
                    ->getOneOrNullResult();

            if (!$presupuesto) {
                throw $this->createNotFoundException("No existe el presupuesto $id");
            }
        } else {
            $presupuesto = new Presupuestos();
        }

        return $presupuesto;
    }

    public function excelAction($id)
    {
        $presupuesto = $this->getPresupuesto($id);

        return $this->prepareExcel(function()use ($presupuesto) {
                            require "/../Resources/views/Presupuesto/presupuesto.excel.php";
                        }, $presupuesto->getTitulo());
    }

    protected function prepareExcel(\Closure $function, $filename = 'report')
    {
        $response = new StreamedResponse($function);
        $response->headers->set('Content-Type', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=\"{$filename}.xlsx\"");
        $response->headers->set('Cache-Control', 'ax-age=0');
        return $response;
    }

}