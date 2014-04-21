<?php

namespace K2\PresupuestoBundle\Controller;

use Closure;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Model\PresupuestoManager;
use K2\PresupuestoBundle\Report;
use K2\PresupuestoBundle\Response\ErrorResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PresupuestoController extends Controller
{

    /**
     * 
     * @return PresupuestoManager
     */
    protected function getManager()
    {
        return $this->get('presupuesto.manager');
    }

    /**
     * 
     * @param type $page
     * @return type
     * @Template()
     */
    public function listadoAction($page)
    {
        $query = $this->getManager()
                ->getRepository()
                ->queryAll();

        $presupuestos = $this->get("knp_paginator")
                ->paginate($query, $page);

        return array(
            'presupuestos' => $presupuestos,
        );
    }

    /**
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     * @Template("PresupuestoBundle:Presupuesto:presupuesto.html.twig")
     */
    public function edicionAction(Request $request, Presupuestos $presupuesto = null)
    {
        $form = $this->getManager()->getForm($presupuesto);
        
        $serializer = $this->get('jms_serializer');
        
//        var_dump($serializer->serialize($form->getData(), 'json'));die;
        return array(
            'form' => $form->createView(),
            'presupuesto' => $form->getData(),
        );
    }

    /**
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     * @Template("PresupuestoBundle:Presupuesto:presupuesto.html.twig")
     */
    public function saveAction(Request $request, Presupuestos $presupuesto = null)
    {
        $isNew = $presupuesto === null;
        $manager = $this->getManager();
        $form = $manager->getForm($presupuesto);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $presupuesto = $form->getData();
            $manager->save($presupuesto);

            return new Response($presupuesto->getId());
        } else {
            return new ErrorResponse($form->getErrors());
        }
    }

    /**
     * @ParamConverter("presupuesto", class="PresupuestoBundle:Presupuestos")
     */
    public function exportAction(Presupuestos $presupuesto)
    {
        return $this->prepareExport(function()use ($presupuesto) {
                    Report\Presupuesto::excel($presupuesto);
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
