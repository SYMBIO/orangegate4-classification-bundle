<?php
/**
 * Created by PhpStorm.
 * User: jiri.bazant
 * Date: 02.09.15
 * Time: 18:28
 */

namespace Symbio\OrangeGate\ClassificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ContextCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('sonata.classification.admin.category');

        $definition->addMethodCall('setTemplate', array('tree', 'SymbioOrangeGateClassificationBundle:CategoryAdmin:tree.html.twig'));
    }
}