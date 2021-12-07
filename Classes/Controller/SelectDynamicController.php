<?php

namespace KayStrobach\Custom\Controller;
use KayStrobach\Custom\View\SelectDynamicView;
use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Persistence\Doctrine\Query;

class SelectDynamicController extends ActionController
{
    /**
     * @var StringFrontend
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

    public function getAlternativesAction(string $identifier, string $labelAttribute, string $q = '')
    {
        $query = $this->cache->get($identifier);
        if (strlen($q) < 1) {
            $this->view->assign('error', 'please provide atleast 3 chars');
            $this->view->assign('success', false);
            return;
        }
        if (!$query instanceof Query) {
            $this->view->assign('error', 'is not a query Result, but a ' . get_class($query));
            $this->view->assign('success', false);
            return;
        }

        $d = $query->getConstraint();

        $query->matching(
            $query->logicalAnd(
                [
                    $d,
                    $query->like(
                        $labelAttribute,
                        '%' . $q . '%'
                    )
                ]
            )
        );
        $query->setLimit(100);
        $result = $query->execute();
        $query->setOrderings([]);

        $this->view->assign('result', $result);
    }
}
