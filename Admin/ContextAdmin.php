<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContextAdmin extends AbstractAdmin
{
    protected $listModes = [
        'list' => [
            'class' => 'fa fa-list fa-fw',
        ],
    ];

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->ifTrue(!($this->hasSubject() && null !== $this->getSubject()->getId()))
            ->add('id')
            ->ifEnd()
            ->add('site')
            ->add('name')
            ->add('enabled', null, ['required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('site', null, [
                'show_filter' => false,
            ])
            ->add('name')
            ->add('enabled')
        ;
    }
}
