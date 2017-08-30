<?php


namespace KayStrobach\Custom\ViewHelpers\Form;

use Neos\FluidAdaptor\Core\ViewHelper\Exception;
use KayStrobach\Custom\Utility\DateStringToMaskUtility;
use Neos\Flow\Annotations as Flow;

class DateTimeTextfieldViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @Flow\Inject
	 * @var DateStringToMaskUtility
	 */
	protected $dateStringUtility;
	/**
	 * @var string
	 */
	protected $tagName = 'input';

	/**
	 * @Flow\Inject()
	 * @var \Neos\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
		$this->registerTagAttribute('maxlength', 'int', 'The maxlength attribute of the input field (will not be validated)');
		$this->registerTagAttribute('readonly', 'string', 'The readonly attribute of the input field');
		$this->registerTagAttribute('size', 'int', 'The size of the input field');
		$this->registerTagAttribute('placeholder', 'string', 'Placeholder for the field');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
		$this->registerArgument('dateFormat', 'string', 'DateTime object attribute', FALSE, 'Y-m-d\TH:i:sP');
		$this->registerUniversalTagAttributes();
	}

	/**
	 * @param boolean $required
	 * @param string $type
	 * @param string $locale
	 * @return string
	 */
	public function render($required = NULL, $type = 'text', $locale = NULL) {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

		$this->tag->addAttribute('type', $type);
		$this->tag->addAttribute('name', $name . '[date]');

		/** @var \DateTime|mixed $value */
		$value = $this->getValue(FALSE);
		if(is_a($value, '\\DateTime')) {
			$valueString = $value->format($this->arguments['dateFormat']);
		} elseif(is_array($value) && array_key_exists('date', $value)) {
			$valueString = $value['date'];
		} else {
			$valueString = '';
		}

		if ($value !== NULL) {
			$this->tag->addAttribute('value', $valueString);
		}

		if ($required !== NULL) {
			$this->tag->addAttribute('required', 'required');
		}

		$this->tag->addAttribute('data-field-render', 'datepicker');
		$this->tag->addAttribute('data-format', $this->arguments['dateFormat']);
		$this->tag->addAttribute('data-date-format', $this->arguments['dateFormat']);

		$this->tag->addAttribute('data-mask', $this->dateStringUtility->convert($this->arguments['dateFormat']));

		if($locale === NULL) {
			$locale = $this->localizationService->getConfiguration()->getCurrentLocale();
			$this->tag->addAttribute('data-date-locale', $locale->getLanguage());
		} else {
			$this->tag->addAttribute('data-date-locale', $locale);
		}


		$this->setErrorClassAttribute();

		return $this->tag->render() . $this->renderHiddenFields();
	}

	protected function renderHiddenFields()
    {
        return '<input type="hidden" value="' . htmlspecialchars($this->arguments['dateFormat']) . '" name="' . $this->getName() . '[dateFormat]" />'
            . '<input type="hidden" value="' . htmlspecialchars($this->dateStringUtility->convertToUnderscores($this->arguments['dateFormat'])) . '" name="' . $this->getName() . '[dateMask]" />';
    }
}