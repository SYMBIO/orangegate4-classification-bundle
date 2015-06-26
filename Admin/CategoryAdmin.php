<?php

namespace Symbio\OrangeGate\ClassificationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends \Sonata\ClassificationBundle\Admin\CategoryAdmin
{
    protected $listModes = array(
        'tree' => array(
            'class' => 'fa fa-list fa-fw',
        ),
    );

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
            $parameters['context'] = $this->getRequest()->get('context');
            $parameters['site'] = $this->getRequest()->get('site');

            return $parameters;
        }

        return $parameters;
    }
}
