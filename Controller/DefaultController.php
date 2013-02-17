<?php

namespace K2\PresupuestoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PresupuestoBundle:Default:index.html.twig', array('name' => $name));
    }
}
