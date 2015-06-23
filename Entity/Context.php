<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseContext;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classification__context")
 */
class Context extends BaseContext
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Symbio\OrangeGate\PageBundle\Entity\Site", cascade={"persist"})
     * @ORM\JoinColumn(name="site_id", nullable=true)
     */
    private $site;

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Context
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set site
     *
     * @param Site $site
     * @return Context
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }
}
