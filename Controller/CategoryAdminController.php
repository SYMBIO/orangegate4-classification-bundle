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
        $currentSite = $sitesPool->getCurrentSite($request);

        $currentContext = $this->admin->getPersistentParameter('context');

        $filterParams = $this->admin->getFilterParameters();
        if (isset($filterParams['context']['value']) && $filterParams['context']['value']) {
            $currentContext = $filterParams['context']['value'];
        }

        $currentContext = $this->get('sonata.classification.manager.context')->findOneBy(array('id' => $currentContext));

        if (!$currentContext || $currentContext->getSite() !== $currentSite) {
            $rootCategories = $categoryManager->getRootCategoriesForSite($currentSite, false);
            $mainCategory   = current($rootCategories);
            $currentContext = $mainCategory->getContext();
        } else {
            $mainCategory = $categoryManager->getRootCategoryForContext($currentContext);
            $rootCategories = array($mainCategory);
        }

        if ($currentContext && !isset($mainCategory)) {
            $mainCategory = $categoryManager->create();
            $mainCategory->setName($currentContext->getName());
            $mainCategory->setContext($currentContext);
            $mainCategory->setEnabled(true);
            $categoryManager->save($mainCategory);
        }

        $datagrid = $this->admin->getDatagrid();

        if ($currentContext) {
            $datagrid->setValue('context', ChoiceType::TYPE_EQUAL, $currentContext->getId());
        }

        $formView = $datagrid->getForm()->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('tree'), array(
            'action'           => 'tree',
            'main_category'    => $mainCategory,
            'root_categories'  => $rootCategories,
            'current_context'  => $currentContext,
            'sites'            => $sitesPool->getSites(),
            'currentSite'      => $currentSite,
            'form'             => $formView,
            'csrf_token'       => $this->getCsrfToken('sonata.batch'),
        ));
    }
}
