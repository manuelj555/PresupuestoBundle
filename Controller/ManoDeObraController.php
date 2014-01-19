<?php

namespace K2\PresupuestoBundle\Controller;

use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use K2\PresupuestoBundle\Model\ManoDeObraManager;
use K2\PresupuestoBundle\Response\ErrorResponse;
use K2\PresupuestoBundle\Response\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ManoDeObraController extends Controller
{

    public function listadoAction(Request $request, $page, $description)
    {
        $manager = $this->getManager();

        if ($request->isMethod('POST')) {
            $description = $this->getRequest()->request->get("description");
            return $this->redirect($this->generateUrl('manosdeobra_listado'
                                    , array('description' => $description)));
        }

        $query = $manager->getManoDeObraRepository()
                ->queryAllManosDeObra();

        $registros = $this->get("knp_paginator")->paginate($query, $page);

//        if ($page > 1 and $registros->count() == 0) {
//            throw $this->createNotFoundException("No existe la pagina $page en los resultados de la consulta de las manos de obra");
//        }

        $form = $manager->getForm(new ManosDeObra());

        return $this->render("PresupuestoBundle:ManoDeObra:listado.html.twig"
                        , array(
                    'manosdeobra' => $registros,
                    'description' => $description,
                    'form' => $form->createView(),
        ));
    }

    public function agregarAction(Request $request)
    {
        $manager = $this->getManager();
        $manoDeObra = new ManosDeObra();

        $form = $manager->getForm($manoDeObra);

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($manoDeObra);
                $em->flush();
                return new SuccessResponse("La Mano de Obra se Guardó con exito");
            } else {
                return new ErrorResponse($form->getErrors(), ErrorResponse::ALERT_FORM);
            }
        }

        return $this->agregarResponse($form);
    }

    public function editarAction($id)
    {
        $manoDeObra = $this->getEntity($id);

        $form = $this->createForm(new ManoDeObraForm(), $manoDeObra);

        if ($this->getRequest()->isMethod('POST')) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $form->bind($data[$form->getName()]);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($manoDeObra);
                $em->flush();
                return new SuccessResponse("La Mano de Obra se Guardó con exito", 'EditManoDeObraSuccess');
            } else {
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

    public function getAllAction()
    {
        $result = $this->getManager()
                ->getAllManosDeObra();

        return $this->render("PresupuestoBundle:ManoDeObra:all.ajax.html.twig"
                        , array(
                    'manosdeobra' => $result,
        ));
    }

    public function jsonAction()
    {
        $result = $this->getManager()
                ->getAllManosDeObra();

        return new JsonResponse($result);
    }

    /**
     * 
     * @return ManoDeObraManager
     */
    protected function getManager()
    {
        return $this->get('presupuesto.manodeobra_manager');
    }

}
