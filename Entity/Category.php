<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Entity\BaseCategory;
use Sonata\MediaBundle\Model\MediaInterface;

#[ORM\Entity]
#[ORM\Table(name: 'classification__category')]
class Category extends BaseCategory
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Symbio\OrangeGate\MediaBundle\Entity\Media')]
    #[ORM\JoinColumn(name: 'media_id', referencedColumnName: 'id', nullable: true)]
    protected ?object $media = null;

    public function __construct()
    {
        parent::__construct();
        $this->enabled = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContext(): ?Context
    {
        $context = parent::getContext();

        return $context instanceof Context ? $context : null;
    }

    public function getParent(): ?self
    {
        $parent = parent::getParent();

        return $parent instanceof self ? $parent : null;
    }

    public function setMedia(?MediaInterface $media): void
    {
        $this->media = $media;
    }

    public function getMedia(): ?object
    {
        return $this->media;
    }
}
