<?php

namespace Symbio\OrangeGate\ClassificationBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Category Admin Controller
 */
class CollectionAdminController extends CRUDController
{
    /**
     * List action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws AccessDeniedException If access is not granted
     */
    public function listAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $sitesPool = $this->get('orangegate.site.pool');
        $sites = $sitesPool->getSites();
        $currentSite = $sitesPool->getCurrentSite($request, $sites);

        $datagrid = $this->admin->getDatagrid();
        $datagrid->setValue('context__site', null, $currentSite->getId());
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'      => 'list',
            'form'        => $formView,
            'datagrid'    => $datagrid,
            'sites'       => $sites,
            'currentSite' => $currentSite,
            'csrf_token'  => $this->getCsrfToken('sonata.batch'),
        ), null, $request);
    }
}
