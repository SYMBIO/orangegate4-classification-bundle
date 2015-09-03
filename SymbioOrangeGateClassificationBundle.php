<?php

namespace Symbio\OrangeGate\ClassificationBundle;

use Symbio\OrangeGate\ClassificationBundle\DependencyInjection\Compiler\ContextCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymbioOrangeGateClassificationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ContextCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataClassificationBundle';
    }
}