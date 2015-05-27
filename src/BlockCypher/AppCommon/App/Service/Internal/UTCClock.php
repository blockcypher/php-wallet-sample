<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\AppCommon\App\Service\Clock;

class UTCClock implements Clock
{
    /**
     * @return \DateTime
     */
    public function now()
    {
        return new \DateTime(\DateTimeZone::UTC);
    }
}