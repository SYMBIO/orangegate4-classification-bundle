<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Entity\BaseContext;
use Symbio\OrangeGate\PageBundle\Entity\Site;

#[ORM\Entity]
#[ORM\Table(name: 'classification__context')]
class Context extends BaseContext
{
    #[ORM\ManyToOne(targetEntity: Site::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'site_id', nullable: true)]
    private ?Site $site = null;

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function __toString(): string
    {
        if ($this->site) {
            return $this->site->getName().' / '.$this->getName();
        }

        return $this->getName() ?? 'n/a';
    }
}
