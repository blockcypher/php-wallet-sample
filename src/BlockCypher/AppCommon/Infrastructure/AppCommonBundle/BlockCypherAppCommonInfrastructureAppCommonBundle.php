<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle;

use BlockCypher\AppCommon\Infrastructure\AppCommonBundle\DependencyInjection\Security\BlockCypherFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlockCypherAppCommonInfrastructureAppCommonBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new BlockCypherFactory());
    }
}
