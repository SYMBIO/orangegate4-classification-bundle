<?php

namespace Symbio\OrangeGate\ClassificationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateClassificationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataClassificationBundle';
    }
}