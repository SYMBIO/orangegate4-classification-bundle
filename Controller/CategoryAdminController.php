<?php

namespace Symbio\OrangeGate\ClassificationBundle\Controller;

use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\ClassificationBundle\Controller\CategoryAdminController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Category Admin Controller
 */
class CategoryAdminController extends BaseController
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
        return $this->treeAction($request);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function treeAction(Request $request)
    {
        $categoryManager = $this->get('sonata.classification.manager.category');

        $sitesPool = $this->get('orangegate.site.pool');
        $sites = $sitesPool->getSites();
        $currentSite = $sitesPool->getCurrentSite($request, $sites);

        $currentContext = false;
        if ($context = $request->get('context')) {
            $currentContext = $this->get('sonata.classification.manager.context')->find($context);
        }

        $rootCategories = $categoryManager->getRootCategoriesForSite($currentSite, false);

        if (!$currentContext || $currentContext->getSite() !== $currentSite) {
            $mainCategory   = current($rootCategories);
            $currentContext = $mainCategory->getContext();
        } else {
            foreach($rootCategories as $category) {
                if ($currentContext->getId() != $category->getContext()->getId()) {
                    continue;
                }

                $mainCategory = $category;

                break;
            }
        }

        if ($currentContext && !isset($mainCategory)) {
            $mainCategory = $categoryManager->create();
            $mainCategory->setName($currentContext->getName());
            $mainCategory->setContext($currentContext);
            $mainCategory->setEnabled(true);
            $categoryManager->save($mainCategory);

            // have to reload categories
            $rootCategories = $categoryManager->getRootCategoriesForSite($currentSite, false);
        }

        $datagrid = $this->admin->getDatagrid();

        if ($this->admin->getPersistentParameter('context')) {
            $datagrid->setValue('context', ChoiceType::TYPE_EQUAL, $this->admin->getPersistentParameter('context'));
        }

        $formView = $datagrid->getForm()->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('tree'), array(
            'action'           => 'tree',
            'main_category'    => $mainCategory,
            'root_categories'  => $rootCategories,
            'current_context'  => $currentContext,
            'sites'            => $sites,
            'currentSite'      => $currentSite,
            'form'             => $formView,
            'csrf_token'       => $this->getCsrfToken('sonata.batch'),
        ));
    }
}
