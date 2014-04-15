<?php

namespace K2\PresupuestoBundle\Controller;

use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Model\ManoDeObraManager;
use K2\PresupuestoBundle\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ManoDeObraController extends Controller
{

    /**
     * 
     * @Template()
     */
    public function listadoAction(Request $request, $page = 1, $description = null)
    {
        $manager = $this->getManager();

        if ($request->isMethod('POST')) {
            $description = $this->getRequest()->request->get("description");
            return $this->redirect($this->generateUrl('manosdeobra_listado'
                                    , array('description' => $description)));
        }

        $query = $manager->getRepository()
                ->queryAllManosDeObra($description);

        $registros = $this->get("knp_paginator")->paginate($query, $page);

//        if ($page > 1 and $registros->count() == 0) {
//            throw $this->createNotFoundException("No existe la pagina $page en los resultados de la consulta de las manos de obra");
//        }

        $form = $manager->getForm(new ManosDeObra());

        return array(
            'manosdeobra' => $registros,
            'description' => $description,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template()
     */
    public function agregarAction(Request $request, ManosDeObra $manoDeObra = null)
    {
        $manager = $this->getManager();

        if (!$manoDeObra) {
            $manoDeObra = new ManosDeObra();
        }

        $form = $manager->getForm($manoDeObra);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($manoDeObra);

            $this->get('session')->getFlashBag()
                    ->add('success', "La Mano de Obra se Guardó con exito");

            return $this->redirect($this->generateUrl('manosdeobra_listado'));
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('@Presupuesto/ManoDeObra/form_ajax.html.twig', array(
                        'form' => $form->createView(),
            ));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * 
     * @ParamConverter("manoDeObra", class="PresupuestoBundle:ManosDeObra")
     * @Template()
     */
    public function editarAction(Request $request, ManosDeObra $manoDeObra)
    {
        return $this->agregarAction($request, $manoDeObra);
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

    public function getMaterialesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $result = $em->getRepository('PresupuestoBundle:ManoDeObraMaterial')
                ->createQueryBuilder('mm')
                ->select('mm, material')
                ->join('mm.material', 'material')
                ->where('mm.manoDeObra = :mdo')
                ->setParameter('mdo', $id)
                ->getQuery()
                ->getArrayResult()
                ;

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
