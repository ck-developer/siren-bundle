<?php

namespace Siren\Bundle\DependencyInjection;

use Siren\Siren;
use Siren\HttpClient\Configurator;
use Siren\Hydrator\ArrayHydrator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

class SirenExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $arrayHydratorDefinition = new Definition(ArrayHydrator::class);

        $container->setDefinition(ArrayHydrator::class, $arrayHydratorDefinition);
        $container->setAlias('siren.hydrator.array', ArrayHydrator::class);

        $clientConfiguratorDefinition = new Definition(Configurator::class);
        $clientConfiguratorDefinition->addMethodCall('setClientKey', [$config['client_key']]);
        $clientConfiguratorDefinition->addMethodCall('setClientSecret', [$config['client_secret']]);

        $container->setDefinition(Configurator::class, $clientConfiguratorDefinition);
        $container->setAlias('siren.client_configurator', Configurator::class);

        $sirenDefinition = new Definition(
            Siren::class,
            [
                new Reference(Configurator::class),
                null,
                null,
                new Reference(ArrayHydrator::class)
            ]
        );

        $container->setDefinition(Siren::class, $sirenDefinition);
        $container->setAlias('siren', Siren::class);
    }
}