<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$treeBuilder
			->root('app')
			->children()
                ->scalarNode('api_public_path')->isRequired()->end()
                ->variableNode('registration_secrets')->isRequired()->end()
			->end();

		return $treeBuilder;
	}
}
