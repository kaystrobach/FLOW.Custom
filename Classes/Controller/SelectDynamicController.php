<?php

namespace KayStrobach\Custom\Controller;
use KayStrobach\Custom\View\SelectDynamicView;
use Neos\Cache\Frontend\VariableFrontend;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Persistence\Doctrine\Query;

class SelectDynamicController extends ActionController
{
    /**
     * @var VariableFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * The default view object to use if none of the resolved views can render
     * a response for the current request.
     *
     * @var string
     * @api
     */
    protected $defaultViewObjectName = SelectDynamicView::class;

    public function getAlternativesAction(string $identifier, string $q = '')
    {
        $cache = $this->cache->get($identifier);
        $query = $cache['query'];
        $labelAttribute = $cache['optionLabelField'] ?? '';
        $valueAttribute = $cache['valueAttribute'] ?? null;

        $this->view->assign(
            'error',
            json_encode([$labelAttribute, $valueAttribute, $q, $cache])
        );

        if (strlen($q) < 1) {
            $this->view->assign('error', 'please provide atleast 3 chars');
            $this->view->assign('success', false);
            return;
        }
        if (!$query instanceof Query) {
            $this->view->assign(
                'error',
                'is not a ' . Query::class . ', but a ' . get_class($query)
            );
            $this->view->assign('success', false);
            return;
        }

        $originalConstraints = $query->getConstraint();
        $result = $query->execute();

        $query->matching(
            $query->logicalAnd(
                [
                    $originalConstraints,
                    $query->like(
                        $labelAttribute,
                        '%' . $q . '%'
                    )
                ]
            )
        )
        ->setLimit(100)
        ->setOrderings(
            [
                $labelAttribute => Query::ORDER_ASCENDING
            ]
        );
        $result = $query->execute();


        if ($valueAttribute !== null) {
            $dQuery = $result->getQuery()->getQueryBuilder();
            $dQuery->orWhere(
                $dQuery->expr()->eq(
                    $dQuery->getRootAliases()[0]. '.Persistence_Object_Identifier',
                    $dQuery->expr()->literal($valueAttribute)
                )
            );
            $result = $dQuery->getQuery()->execute();
        }

        $this->view->assign('result', $result);
    }
}
