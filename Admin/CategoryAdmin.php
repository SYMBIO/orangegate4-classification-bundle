<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\ClassificationBundle\Admin\ContextAwareAdmin;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Symbio\OrangeGate\ClassificationBundle\Entity\Context;
use Symbio\OrangeGate\PageBundle\Entity\SitePool;

class CategoryAdmin extends ContextAwareAdmin
{
    protected $translationDomain = 'SonataClassificationBundle';

    protected $listModes = [
        'tree' => [
            'class' => 'fa fa-list fa-fw',
        ],
    ];

    protected SitePool $sitePool;

    public function __construct(
        $code,
        $class,
        $baseControllerName,
        ContextManagerInterface $contextManager,
        SitePool $sitePool,
    ) {
        parent::__construct($code, $class, $baseControllerName, $contextManager);

        $this->sitePool = $sitePool;
    }

    /**
     * @return array<int, object>
     */
    public function getContextList(): array
    {
        $criteria = [
            'site' => $this->sitePool->getCurrentSite($this->getRequest()),
        ];

        return $this->contextManager->findBy($criteria, ['name' => 'asc']);
    }

    protected function configurePersistentParameters(): array
    {
        $parameters = [
            'site' => '',
            'context' => '',
            'hide_context' => $this->hasRequest() ? $this->getRequest()->query->getInt('hide_context', 0) : 0,
        ];

        if ($this->hasSubject()) {
            $context = $this->getSubject()->getContext();
            $parameters['context'] = null !== $context ? $context->getId() : '';
            $parameters['site'] = ($context instanceof Context && null !== $context->getSite())
                ? $context->getSite()->getId()
                : '';

            return $parameters;
        }

        if ($this->hasRequest()) {
            $filter = $this->getRequest()->get('filter');
            if (\is_array($filter) && isset($filter['context'])) {
                $context = $filter['context']['value'];
            } else {
                $context = $this->getRequest()->get('context', false);
                $availableContexts = array_map(static fn ($c) => $c->getId(), $this->getContextList());
                if (!$context || !\in_array($context, $availableContexts, true)) {
                    $context = $availableContexts[0] ?? '';
                }
            }

            $parameters['context'] = $context;
            $parameters['site'] = $this->getRequest()->get('site');
        }

        return $parameters;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('name')
            ->add('enabled')
        ;
    }

    public function prePersist(object $object): void
    {
        if (null === $object->getContext() && null !== $object->getParent()?->getContext()) {
            $object->setContext($object->getParent()->getContext());
        }
    }

    public function preUpdate(object $object): void
    {
        $this->prePersist($object);
    }
}
