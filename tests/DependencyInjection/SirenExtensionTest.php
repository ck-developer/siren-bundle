<?php

namespace Siren\Bundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siren\Bundle\DependencyInjection\SirenExtension;
use Siren\HttpClient\Configurator;
use Siren\Hydrator\ArrayHydrator;
use Siren\Siren;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SirenExtensionTest extends TestCase
{
    public function testLoad()
    {
        $extension = new SirenExtension();

        $extension->load(
            [
                'siren' => [
                    'client_key' => 'client_key',
                    'client_secret' => 'client_secret',
                ]
            ],
            $container = new ContainerBuilder()
        );

        $this->assertTrue($container->hasDefinition(ArrayHydrator::class));
        $this->assertTrue($container->hasAlias('siren.hydrator.array'));

        $this->assertTrue($container->hasDefinition(Configurator::class));
        $this->assertEquals(
            [
                [
                    'setClientKey',
                    [
                        'client_key'
                    ]
                ],
                [
                    'setClientSecret',
                    [
                        'client_secret'
                    ]
                ]
            ],
            $container->getDefinition(Configurator::class)->getMethodCalls()
        );
        $this->assertTrue($container->hasAlias('siren.client_configurator'));

        $this->assertTrue($container->hasDefinition(Siren::class));
        $this->assertTrue($container->hasAlias('siren'));
    }
}