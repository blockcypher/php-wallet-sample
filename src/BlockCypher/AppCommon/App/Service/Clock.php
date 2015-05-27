<?php

namespace BlockCypher\AppCommon\App\Service;

interface Clock
{
    /**
     * @return \DateTime
     */
    public function now();
}