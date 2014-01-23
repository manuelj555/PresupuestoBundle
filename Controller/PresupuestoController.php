<?php

namespace K2\PresupuestoBundle\Controller;

use Closure;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Model\PresupuestoManager;
use K2\PresupuestoBundle\Report;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Response\RedirectResponse;
use K2\PresupuestoBundle\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $isNew = $presupuesto === null;
        $manager = $this->getManager();
        $form = $manager->getForm($presupuesto);

        if ($this->getRequest()->isMethod('POST')) {

            $data = json_decode($request->getContent(), true);
            $form->bind($data[$form->getName()]);

            if ($form->isValid()) {
                $manager->save($presupuesto);
                if ($isNew) {
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

        return array(
            'form' => $form->createView(),
            'presupuesto' => $form->getData(),
        );
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
