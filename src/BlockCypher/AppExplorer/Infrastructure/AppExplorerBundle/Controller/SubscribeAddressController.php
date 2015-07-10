<?php

namespace BlockCypher\AppExplorer\Infrastructure\AppExplorerBundle\Controller;

use BlockCypher\AppExplorer\Presentation\Facade\AddressServiceFacade;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class SubscribeAddressController extends AppExplorerController
{
    /**
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param Session $session
     * @param AddressServiceFacade $walletServiceFacade
     */
    public function __construct(
        EngineInterface $templating,
        TranslatorInterface $translator,
        Session $session,
        AddressServiceFacade $walletServiceFacade
    )
    {
        parent::__construct($templating, $translator, $session);
        $this->addressServiceFacade = $walletServiceFacade;
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