<?php

namespace BlockCypher\AppCommon\Domain;

interface ArrayConversion
{
    /**
     * @param array $entityAsArray
     * @return mixed
     */
    public static function fromArray($entityAsArray);

    /**
     * @return array
     */
    public function toArray();
}