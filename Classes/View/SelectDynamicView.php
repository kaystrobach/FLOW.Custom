<?php

namespace KayStrobach\Custom\View;

use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

class SelectDynamicView extends JsonView implements ViewInterface
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    protected function renderArray()
    {
        $result = $this->variables['result'] ?? [];
        $resultArray = [
            'results' => [],
            'success' => $this->variables['success'] ?? false,
            'message' => $this->variables['error'] ?? '',
            'pagination' => [
                'more' => false
            ]
        ];

        foreach ($result as $item)
        {
            $identifier = $this->persistenceManager->getIdentifierByObject($item);
            // if ($item instanceof )
            if (method_exists($item, '__toString')) {
                $resultArray['results'][] = [
                    'id' => $identifier,
                    'text' => (string)$item,
                ];
            }
        }
        return $resultArray;
    }
}
