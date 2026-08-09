<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Entity\BaseTag;

#[ORM\Entity]
#[ORM\Table(name: 'classification__tag')]
class Tag extends BaseTag
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContext(): ?Context
    {
        $context = parent::getContext();

        return $context instanceof Context ? $context : null;
    }
}
