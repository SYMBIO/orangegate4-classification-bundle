<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCategory as BaseCategory;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classification__category")
 */
class Category extends BaseCategory
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
     * @ORM\JoinColumn(name="context_id", referencedColumnName="id", nullable=true)
     */
    protected $context;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="Symbio\OrangeGate\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set context
     *
     * @param \Symbio\OrangeGate\ClassificationBundle\Entity\Context $context
     * @return Category
     */
    public function setContext(\Sonata\ClassificationBundle\Model\ContextInterface $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return \Symbio\OrangeGate\ClassificationBundle\Entity\Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set parent
     *
     * @param \Symbio\OrangeGate\ClassificationBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Sonata\ClassificationBundle\Model\CategoryInterface $parent = null, $nested = false)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Symbio\OrangeGate\ClassificationBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set media
     *
     * @param \Symbio\OrangeGate\MediaBundle\Entity\Media $media
     * @return Category
     */
    public function setMedia(\Sonata\MediaBundle\Model\MediaInterface $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Symbio\OrangeGate\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}
