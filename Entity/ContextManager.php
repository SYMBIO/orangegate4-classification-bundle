<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Doctrine\ORM\QueryBuilder;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Sonata\ClassificationBundle\Entity\ContextManager as BaseContextManager;

use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;

class ContextManager extends BaseContextManager implements ContextManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPager(array $criteria, $page, $limit = 10, array $sort = array())
    {
        $parameters = array();

        /**
         * @var QueryBuilder $query
         */
        $query = $this->getRepository()
            ->createQueryBuilder('c')
            ->select('c');

        if (isset($criteria['site'])) {
            $query->andWhere('c.site = :site');
            $paremeters['site'] = $criteria['site'];
        }

        if (isset($criteria['enabled'])) {
            $query->andWhere('c.enabled = :enabled');
            $parameters['enabled'] = (bool) $criteria['enabled'];
        }

        if ($sort) {
            foreach ($sort as $k => $v) {
                $query->addOrderBy($k, $v);
            }
        } else {
            $query->addOrderBy('c.name', 'ASC');
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
