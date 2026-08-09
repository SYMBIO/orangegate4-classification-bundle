<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Entity\BaseCollection;

#[ORM\Entity]
#[ORM\Table(name: 'classification__collection')]
class Collection extends BaseCollection
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Symbio\OrangeGate\MediaBundle\Entity\Media')]
    #[ORM\JoinColumn(name: 'media_id', referencedColumnName: 'id', nullable: true)]
    protected ?object $media = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
