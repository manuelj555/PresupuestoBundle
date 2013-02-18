<?php

namespace K2\PresupuestoBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ViewSelector
{

    /**
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Selecciona una vista dependiendo de si la petición es ajax ó no
     * @param string $view
     */
    public function select($view)
    {
        if ($this->container->get('request')->isXmlHttpRequest()) {
            return $view . '.html.ajax.twig';
        }
        return $view . '.html.twig';
    }

    public function option($normal, $ajax)
    {
        if ($this->container->get('request')->isXmlHttpRequest()) {
            return is_callable($normal) ? call_user_func($normal, $this->container) : $normal;
        } else {
            return is_callable($ajax) ? call_user_func($ajax, $this->container) : $ajax;
        }
    }

}