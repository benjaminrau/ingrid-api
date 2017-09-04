<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppExtension extends Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');

        $configuration = new Configuration();
        $processedConfiguration = $this->processConfiguration($configuration, $configs);
        $container->setParameter('app.registration_secrets',$processedConfiguration['registration_secrets']);
        $container->setParameter('app.api_public_path',$processedConfiguration['api_public_path']);
	}
}
