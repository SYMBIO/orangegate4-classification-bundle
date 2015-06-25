<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\PagerInterface;

use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;

use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Sonata\CoreBundle\Model\BaseEntityManager;

use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Symbio\OrangeGate\PageBundle\Entity\Site;

class CategoryManager extends \Sonata\ClassificationBundle\Entity\CategoryManager implements CategoryManagerInterface
{
    /**
     * @param string                  $class
     * @param ManagerRegistry         $registry
     * @param ContextManagerInterface $contextManager
     */
    public function __construct($class, ManagerRegistry $registry, ContextManagerInterface $contextManager)
    {
        parent::__construct($class, $registry, $contextManager);
    }

    /**
     * Returns a pager to iterate over the root category
     *
     * @param integer $page
     * @param integer $limit
     * @param array   $criteria
     *
     * @return mixed
     */
    public function getRootCategoriesPager($page = 1, $limit = 25, $criteria = array())
    {
        $page = (int) $page == 0 ? 1 : (int) $page;

        /**
         * @var QueryBuilder $queryBuilder
         */
        $queryBuilder = $this->getObjectManager()->createQueryBuilder()
            ->select('c')
            ->from($this->class, 'c')
            ->andWhere('c.parent IS NULL');

        if (isset($criteria['site'])) {
            $queryBuilder->innerJoin('c.context', 'cc');
            $queryBuilder->andWhere('cc.site = :site');
            $queryBuilder->setParameter('site', $criteria['site']);
        }

        $pager = new Pager($limit);
        $pager->setQuery(new ProxyQuery($queryBuilder));
        $pager->setPage($page);
        $pager->init();

        return $pager;
    }

    /**
     * @return CategoryInterface[]
     */
    public function getRootCategoriesForSite(Site $site, $loadChildren = true)
    {
        $class = $this->getClass();

        $rootCategories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c INNER JOIN c.context cc WHERE c.parent IS NULL AND cc.site = :site', $class))
            ->setParameter('site', $site)
            ->execute();

        $categories = array();

        foreach($rootCategories as $category) {
            $categories[$category->getContext()->getId()] = $loadChildren ? $this->getRootCategory($category->getContext()) : $category;
        }

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function getPager(array $criteria, $page, $limit = 10, array $sort = array())
    {
        $parameters = array();

        $query = $this->getRepository()
            ->createQueryBuilder('c')
            ->select('c');

        if (isset($criteria['context'])) {
            $query->andWhere('c.context = :context');
            $parameters['context'] = $criteria['context'];
        }

        if (isset($criteria['site'])) {
            $query->innerJoin('c.context', 'cc');
            $query->andWhere('cc.site = :site');
            $parameters['site'] = $criteria['site'];
        }

        if (isset($criteria['enabled'])) {
            $query->andWhere('c.enabled = :enabled');
            $parameters['enabled'] = (bool) $criteria['enabled'];
        }

        $query->setParameters($parameters);

        $pager = new Pager();
        $pager->setMaxPerPage($limit);
        $pager->setQuery(new ProxyQuery($query));
        $pager->setPage($page);
        $pager->init();

        return $pager;
    }
}
