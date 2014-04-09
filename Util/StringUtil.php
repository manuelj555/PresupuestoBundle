<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace K2\PresupuestoBundle\Util;

/**
 * Description of StringUtil
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class StringUtil
{

    /**
     * 
     * @param type $string
     * @return type
     */
    public static function createIdentifier($string)
    {
        $identifier = str_replace(' ', '_', strtolower($string));

        return $identifier;
    }

}
