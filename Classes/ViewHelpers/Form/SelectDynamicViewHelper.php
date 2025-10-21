<?php

namespace KayStrobach\Custom\ViewHelpers\Form;
use KayStrobach\Custom\Domain\Session\SelectDynamicStorage;
use KayStrobach\Custom\Traits\SelectPrePopulateTrait;
use Neos\Cache\Frontend\VariableFrontend;
use Neos\Flow\Persistence\Doctrine\QueryResult;
use Neos\FluidAdaptor\Core\ViewHelper;
use Neos\Utility\ObjectAccess;
use Psr\Log\LoggerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * This view helper generates a <select> dropdown list for the use with a form.
 * But in addition to the one in FLOW it allows to specify an empty value as top element
 */
class SelectDynamicViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Form\SelectViewHelper
{
    use SelectPrePopulateTrait;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var VariableFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * Initialize arguments.
     *
     * @return void
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('nothingSelectedLabel', 'string', 'If specified an optionTag with value=NULL is prepended to the list.', false, null);
    }

    /**
     * Render the option tags.
     *
     * @param array $options the options for the form.
     * @return string rendered tags.
     */
    protected function renderOptionTags($options)
    {
        if ($this->arguments['nothingSelectedLabel'] !== null) {
            $this->arguments['prependOptionLabel'] = $this->arguments['nothingSelectedLabel'];
        }
        return parent::renderOptionTags($options);
    }

    /**
     * Render the option tags.
     *
     * @return array an associative array of options, key will be the value of the option tag
     * @throws ViewHelper\Exception
     */
    protected function getOptions(): array
    {
        if (!is_array($this->arguments['options']) && !($this->arguments['options'] instanceof \Traversable)) {
            return [];
        }
        if (is_array($this->arguments['options'])) {
            return $this->prepopulateOptions();
        }
        if ($this->arguments['options'] instanceof QueryResult) {
            $query = $this->arguments['options'];

            $identifier = $this->getPropertyValue() ? $this->persistenceManager->getIdentifierByObject($this->getPropertyValue()) : null;

            $cacheKey = sha1(
                json_encode(
                    [
                        'query' => $query->getQuery()->getSql(),
                        'valueAttribute' => (string)$identifier,
                        'optionLabelField' => $this->arguments['optionLabelField'],
                        'searchField' => $this->arguments['optionLabelField']
                    ],
                    JSON_THROW_ON_ERROR
                )
            );
            $ajaxUri = $this->controllerContext->getUriBuilder()->uriFor(
                'getAlternatives',
                [
                    'identifier' => $cacheKey,
                    'labelAttribute' => $this->arguments['optionLabelField']
                ],
                'SelectDynamic',
                'KayStrobach.Custom'
            );

            $this->tag->addAttribute('data-ajax--url', $ajaxUri);

            $this->cache->set(
                $cacheKey,
                [
                    'query' => $query->getQuery(),
                    'valueAttribute' => $identifier,
                    'optionLabelField' => $this->arguments['optionLabelField'],
                    'searchField' => $this->arguments['optionLabelField']
                ]
            );

            return $this->prepopulateOptions();
        }

        return parent::getOptions();
    }
}
