<?php


namespace KayStrobach\Custom\ViewHelpers\Form;

use TYPO3\Fluid\Core\ViewHelper\Exception;

class DateTimeTextfieldViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {
	/**
	 * @var string
	 */
	protected $tagName = 'input';

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

	public function render($required = NULL, $type = 'text') {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

		$this->tag->addAttribute('type', $type);
		$this->tag->addAttribute('name', $name . '[date]');

		/** @var \DateTime $value */
		$value = $this->getValue(FALSE);
		if(is_a($value, '\\DateTime')) {
			$valueString = $value->format($this->arguments['dateFormat']);
		} else {
			throw new Exception('Expected \\DateTime object, but got ' . gettype($value) . ' for ' . $this->getName());
		}

		if ($value !== NULL) {
			$this->tag->addAttribute('value', $valueString);
		}

		if ($required !== NULL) {
			$this->tag->addAttribute('required', 'required');
		}

		$this->setErrorClassAttribute();

		return $this->tag->render() . '<input type="hidden" value="' . htmlspecialchars($this->arguments['dateFormat']) . '" name="' . $this->getName() . '[dateFormat]" />';
	}
}
