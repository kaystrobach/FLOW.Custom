<?php

namespace KayStrobach\Custom\View;


use Neos\Error\Messages\Message;
use Neos\Error\Messages\Result;
use Neos\Flow\I18n\Translator;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\RequestInterface;
use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Annotations as Flow;


class JsendView extends JsonView implements ViewInterface
{
    /**
     * defines the status of the request
     * @var string
     */
    protected $requestStatus = 'success';

    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    protected function renderArray()
    {
        $valueToRender = array();
        foreach ($this->variablesToRender as $variableName) {
            $valueToRender[$variableName] = isset($this->variables[$variableName]) ? $this->variables[$variableName] : null;
        }
        $configuration = $this->configuration;

        $valueToRender['formErrors'] = $this->getFormErrors();

        if(isset($valueToRender['status'])) {
            $this->requestStatus = $valueToRender['status'];
            unset($valueToRender['status']);
        }

        if(isset($this->variables['redirectTo'])) {
            $valueToRender['redirectTo'] = $this->variables['redirectTo'];
        }

        if(!isset($valueToRender['redirectTo'])) {
            $valueToRender['flashMessages'] = $this->getFlashMessages();
        }

        return array(
            'status' => $this->requestStatus,
            'data' => $this->transformValue($valueToRender, $configuration)
        );
    }

    /**
     * Shortcut for retrieving the request from the controller context
     *
     * @return RequestInterface
     */
    protected function getRequest()
    {
        return $this->controllerContext->getRequest();
    }

    protected function convertMessageToArray(Message $message) {

        $localizedTitle = $this->translator->translateByOriginalLabel(
            $message->getTitle()
        );
        if($message->getCode()) {
            $localizedMessage = $this->translator->translateById(
                $message->getCode(),
                $message->getArguments(),
                null,
                null,
                'ValidationErrors'
            );
        } else {
            $localizedMessage = $this->translator->translateByOriginalLabel(
                $message->getMessage(),
                $message->getArguments(),
                null,
                null,
                'ValidationErrors'
            );
        }


        return array(
            'code' => $message->getCode(),
            'title' => $message->getTitle(),
            'message' => $message->getMessage(),
            'severity' => $message->getSeverity(),
            'arguments' => $message->getArguments(),
            'rendered' => $message->render(),
            'renderedTitleLocalized' => $localizedTitle,
            'renderedMessageLocalized' => $localizedMessage,
        );
    }

    /**
     * @param array $messages
     * @return array
     */
    protected function convertMessagesToArray($messages) {
        $messagesToReturn = array();
        /** @var Message $message */
        foreach($messages as $message) {
            $messagesToReturn[] = $this->convertMessageToArray($message);
            if($message->getSeverity() === Message::SEVERITY_ERROR) {
                $this->requestStatus = 'error';
            }
        }
        return $messagesToReturn;
    }

    protected function getFlashMessages() {
        $messages = $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush();
        return $this->convertMessagesToArray($messages);
    }

    protected function getFormErrors() {
        /** @var Result $validationResults */
        $validationResults = $this->getRequest()->getInternalArgument('__submittedArgumentValidationResults');
        if($validationResults === null) {
            return null;
        }

        $errorsToReturn = array();

        foreach($validationResults->getFlattenedErrors() as $fieldName => $errors) {
            $pos = strpos($fieldName, '.');
            if ($pos !== false) {
                $fieldName = substr_replace($fieldName, '[', $pos, strlen('.'));
            }
            $fieldName = str_replace('.', '][', $fieldName);

            if(strpos($fieldName, '[')) {
                $fieldName = $fieldName . ']';
            }
            $errorsToReturn[$fieldName] = $this->convertMessagesToArray($errors);
        }
        return $errorsToReturn;
    }
}