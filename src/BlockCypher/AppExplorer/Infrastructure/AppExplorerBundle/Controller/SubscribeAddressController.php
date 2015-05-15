<?php

namespace BlockCypher\AppExplorer\Infrastructure\AppExplorerBundle\Controller;

use BlockCypher\AppExplorer\Presentation\Facade\AddressServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscribeAddressController extends AppExplorerController
{
    /**
     * @param EngineInterface $templating
     * @param AddressServiceFacade $addressServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        AddressServiceFacade $addressServiceFacade)
    {
        parent::__construct($templating);
        $this->addressServiceFacade = $addressServiceFacade;
    }

    /**
     * @param Request $request
     * @param string $hash
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, $hash)
    {
        // TODO
    }
}