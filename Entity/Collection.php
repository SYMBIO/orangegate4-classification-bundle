<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classification__collection")
 */
class Collection extends BaseCollection
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Context")
     * @ORM\JoinColumn(name="context", referencedColumnName="id", nullable=true)
     */
    protected $context;

    /**
     * @ORM\ManyToOne(targetEntity="Symbio\OrangeGate\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
}
