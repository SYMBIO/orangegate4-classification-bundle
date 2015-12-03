<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\ClassificationBundle\Entity\ContextManager;
use Symbio\OrangeGate\PageBundle\Entity\SitePool;

class CategoryAdmin extends \Sonata\ClassificationBundle\Admin\CategoryAdmin
{
    protected $translationDomain = 'SonataClassificationBundle';

    protected $listModes = array(
        'tree' => array(
            'class' => 'fa fa-list fa-fw',
        ),
    );

    /**
     * @var SitePool
     */
    protected $sitePool;

    /**
     * @param string         $code
     * @param string         $class
     * @param string         $baseControllerName
     * @param ContextManager $contextManager
     */
    public function __construct($code, $class, $baseControllerName, ContextManager $contextManager, SitePool $sitePool)
    {
        parent::__construct($code, $class, $baseControllerName, $contextManager);

        $this->sitePool = $sitePool;
    }

    /**
     * Returns list of available contexts
     *
     * @return array
     */
    public function getContextList()
    {
        $criteria = array(
            'site' => $this->sitePool->getCurrentSite($this->getRequest())
        );

        return $this->contextManager->findBy($criteria, array('name' => 'asc'));
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $parameters = array(
            'site'         => '',
            'context'      => '',
            'hide_context' => $this->hasRequest() ? (int)$this->getRequest()->get('hide_context', 0) : 0
        );

        if ($this->getSubject()) {
            $parameters['context'] = $this->getSubject()->getContext() ? $this->getSubject()->getContext()->getId() : '';
            $parameters['site'] = $this->getSubject()->getContext() ? $this->getSubject()->getContext() ->getSite()->getId() : '';

            return $parameters;
        }

        if ($this->hasRequest()) {
            if ($filter = $this->getRequest()->get('filter') && isset($filter['context'])) {
                $context = $filter['context']['value'];
            } else {
                $context = $this->getRequest()->get('context', false);
                $available_contexts = array_map(function ($c) { return $c->getId(); }, $this->getContextList());
                if (!$context || !in_array($context, $available_contexts)) {
                    $context = $available_contexts[0];
                }
            }

            $parameters['context'] = $context;
            $parameters['site'] = $this->getRequest()->get('site');
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('context')
            ->add('enabled')
        ;
    }

    public function prePersist($object)
    {
        // make sure that context is set
        // if not try to set it to same as got parent
        if (null === $object->getContext() && $object->getParent()->getContext()) {
            $object->setContext($object->getParent()->getContext());
        }
    }

    public function preUpdate($object)
    {
        $this->prePersist($object);
    }
}
