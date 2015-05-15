<?php

namespace BlockCypher\AppExplorer\Infrastructure\AppExplorerBundle\Controller;

use BlockCypher\AppCommon\Infrastructure\Controller\AppCommonController;

class AppExplorerController extends AppCommonController
{
    public function getBaseTemplatePrefix()
    {
        return 'BlockCypherAppExplorerInfrastructureAppExplorerBundle';
    }
}