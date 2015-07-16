<?php

namespace BlockCypher\AppCommon\Infrastructure\AppCommonBundle\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class BlockCypherFactory
 * @package BlockCypher\AppCommon\Infrastructure\AppCommonBundle\DependencyInjection\Security
 */
class BlockCypherFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.blockcypher.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('blockcypher.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $listenerId = 'security.authentication.listener.blockcypher.' . $id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('blockcypher.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
        //return 'form';
    }

    public function getKey()
    {
        return 'blockcypher';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}