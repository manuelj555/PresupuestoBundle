<?php

namespace K2\PresupuestoBundle\Twig;

use K2\PresupuestoBundle\Util;

class PresupuestoExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'json_entity' => new \Twig_Function_Function(function($object) {
                        return json_encode(Util::normalize($object));
                    }),
            'json_autocomplete' => new \Twig_Function_Function(function($object, $label, $value = null) {
                        $data = array();
                        null === $value && $value = $label;
                        foreach (Util::normalize($object) as $item) {
                            $data[] = array(
                                'value' => $item[$value],
                                'label' => $item[$label],
                            );
                        }
                        return json_encode($data);
                    }),
        );
    }

    public function getName()
    {
        return "presupuesto_extension";
    }

}