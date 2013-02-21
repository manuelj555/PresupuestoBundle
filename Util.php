<?php

namespace K2\PresupuestoBundle;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Util
{

    public static function normalize($data)
    {
        $normalizers = array(new GetSetMethodNormalizer());
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->normalize($data);
    }
}