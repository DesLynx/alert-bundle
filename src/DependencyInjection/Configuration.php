<?php

namespace DesLynx\AlertBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('deslynx_alert');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('from')->isRequired()->info('The sender of the email, like "Name <email@address.com>" or a simple email.')->end()
                ->arrayNode('to')
                    ->performNoDeepMerging()
                    ->beforeNormalization()->castToArray()->end()
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                    ->info('A list of always added recipients for the email, like "Name <email@address.com>" or a simple email.')
                ->end()
                ->scalarNode('projectName')->defaultValue('My project')->info('Your project name. Included in the email subject')->end()
                ->scalarNode('transport')->defaultNull()->info('If you want to use a custom email transport set this to the name of the transport.')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
