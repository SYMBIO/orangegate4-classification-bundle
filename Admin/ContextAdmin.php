<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ContextAdmin extends \Sonata\ClassificationBundle\Admin\ContextAdmin
{
    protected $listModes = array(
        'list' => array(
            'class' => 'fa fa-list fa-fw',
        ),
    );

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->ifTrue(!($this->hasSubject() && $this->getSubject()->getId() !== null))
            ->add('id')
            ->ifEnd()
            ->add('site')
            ->add('name')
            ->add('enabled', null, array('required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('site', null, array(
                'show_filter' => false,
            ))
            ->add('name')
            ->add('enabled')
        ;
    }
}
