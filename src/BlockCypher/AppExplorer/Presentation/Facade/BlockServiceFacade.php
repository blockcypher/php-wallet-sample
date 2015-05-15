<?php

namespace BlockCypher\AppExplorer\Presentation\Facade;

interface BlockServiceFacade
{
    /**
     * @param $hashOrHeight
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getBlockOverview($hashOrHeight, $coinSymbol, $token);

    /**
     * @param $hashOrHeight
     * @param array $params
     * @param $coinSymbol
     * @param $token
     * @return array
     */
    public function getBlockDetails($hashOrHeight, $params, $coinSymbol, $token);
}