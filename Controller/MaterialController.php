<?php

namespace K2\PresupuestoBundle\Controller;

use K2\PresupuestoBundle\Entity\Material;
use K2\PresupuestoBundle\Model\MaterialManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MaterialController extends Controller
{

    /**
     * 
     * @Template()
     */
    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $materiales = $em->getRepository('PresupuestoBundle:Materiales')->findAll();

        return array(
            'materiales' => $materiales,
        );
    }

    /**
     * @param Request $request
     * 
     * @Template()
     */
    public function crearAction(Request $request)
    {
        $material = new Material();

        $manager = $this->get('presupuesto.material_manager');
        /* @var $manager MaterialManager */
        
        $form = $manager->getForm($material);
                
        $form->handleRequest($request);        
        
        if ($form->isValid()) {
            $manager->persist($mataerial);

            $request->getSession()->getFlashBag()
                    ->add('sucess', sprintf('Material "%s" Creado!', $material));

            return $this->redirect($this->generateUrl('materiales_listado'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * 
     * @ParamConverter("material", class="PresupuestoBundle:Material")
     * @Template()
     */
    public function editarAction(Request $request, Material $material)
    {
        $manager = $this->get('presupuesto.material_manager');
        /* @var $manager MaterialManager */
        
        $form = $manager->getForm($material);
                
        $form->handleRequest($request);        
        
        if ($form->isValid()) {
            $manager->persist($material);

            $request->getSession()->getFlashBag()
                    ->add('sucess', sprintf('Material "%s" Actualizado!', $material));

            return $this->redirect($this->generateUrl('materiales_listado'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
