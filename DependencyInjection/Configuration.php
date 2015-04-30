<?php

/**
 * @package DataTreeBundle
 *
 * @author ML
 *
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

namespace Fascinosum\DataTreeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree
     *
     * @return object $treeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fascinosum_data_tree');

        $rootNode
            ->children()
                ->scalarNode('txt_model')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
