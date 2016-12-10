<?php

namespace K2\PresupuestoBundle\Controller;

use Closure;
use K2\PresupuestoBundle\Entity\Presupuesto;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Model\PresupuestoManager;
use K2\PresupuestoBundle\Report;
use K2\PresupuestoBundle\Response\ErrorResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/new/", name="presupuesto_crear")
     */
    public function newAction()
    {
        $presupuesto = new Presupuesto();
        $em = $this->getDoctrine()->getManager();

        $em->persist($presupuesto);
        $em->flush();

        return $this->redirectToRoute('presupuesto_editar', [
            'id' => $presupuesto->getId(),
        ]);
    }

    /**
     * @Route("/edit/{id}/", name="presupuesto_editar")
     */
    public function edicionAction(Presupuesto $presupuesto)
    {
        return $this->render('@Presupuesto/presupuesto.html.twig', [
            'presupuesto' => $presupuesto,
        ]);
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
