<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\ClassificationBundle\Admin\ContextAwareAdmin;

class CollectionAdmin extends ContextAwareAdmin
{
    protected $listModes = [
        'tree' => [
            'class' => 'fa fa-list fa-fw',
        ],
    ];

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('enabled', null, ['required' => false])
            ->add('name')
            ->add('description', 'textarea', ['required' => false])
            ->add('context')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('enabled')
            ->add('context.site', null, [
                'show_filter' => false,
            ])
            ->add('context')
        ;
    }
}
