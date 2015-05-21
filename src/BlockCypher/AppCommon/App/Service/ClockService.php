<?php

namespace BlockCypher\AppCommon\App\Service;

interface ClockService
{
    /**
     * @return \DateTime
     */
    public function now();
}