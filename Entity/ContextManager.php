<?php

declare(strict_types=1);

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Sonata\Doctrine\Entity\BaseEntityManager;

/**
 * @phpstan-extends BaseEntityManager<ContextInterface>
 */
class ContextManager extends BaseEntityManager implements ContextManagerInterface
{
}
