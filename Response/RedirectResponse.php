<?php

namespace K2\PresupuestoBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class RedirectResponse extends JsonResponse
{
    public function __construct($url)
    {
        parent::__construct($url);
        $this->setCallback("redirect");
    }

}
