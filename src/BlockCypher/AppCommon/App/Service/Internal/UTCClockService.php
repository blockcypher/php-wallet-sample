<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\AppCommon\App\Service\ClockService;

class UTCClockService implements ClockService
{
    /**
     * @return \DateTime
     */
    public function now()
    {
        return new \DateTime(\DateTimeZone::UTC);
    }
}