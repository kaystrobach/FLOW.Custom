<?php

namespace KayStrobach\Custom\ViewHelpers\Form;
use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Persistence\Doctrine\QueryResult;
use Neos\FluidAdaptor\Core\ViewHelper;
use Psr\Log\LoggerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * This view helper generates a <select> dropdown list for the use with a form.
 * But in addition to the one in FLOW it allows to specify an empty value as top element
 */
class SelectDynamicViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Form\SelectViewHelper
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var StringFrontend
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
    protected function getOptions()
    {
        if (!is_array($this->arguments['options']) && !($this->arguments['options'] instanceof \Traversable)) {
            return [];
        }
        if (is_array($this->arguments['options'])) {
            return parent::getOptions();
        }
        if ($this->arguments['options'] instanceof QueryResult) {
            $cacheKey = sha1($this->arguments['options']->getQuery()->getSql());
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
                $this->arguments['options']->getQuery()
            );

            $query = $this->arguments['options']->getQuery()->getQueryBuilder()->setCacheable(true);
            $query->andWhere(
                $query->expr()->eq(
                    $query->getRootAliases()[0]. '.Persistence_Object_Identifier',
                    $query->expr()->literal($this->getValueAttribute()
                    )
                )
            );
            $this->arguments['options'] = $query->getQuery()->execute();
        }

        return parent::getOptions();
    }
}
