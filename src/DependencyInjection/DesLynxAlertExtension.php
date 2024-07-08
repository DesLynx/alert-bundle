<?php

namespace DesLynx\AlertBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DesLynxAlertExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('deslynx_alert.service.alert_helper');
        $definition->setArgument(3, $config['from']);
        $definition->setArgument(4, $config['to']);
        $definition->setArgument(5, $config['projectName']);
        $definition->setArgument(6, $config['transport']);
    }

    public function getAlias(): string
    {
        return 'deslynx_alert';
    }
}
