<?php
namespace KayStrobach\Custom\Command;

/*
 * This file is part of the KayStrobach.Custom package.
 */

use KayStrobach\Custom\Utility\MailUtility;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class MailCommandController extends \Neos\Flow\Cli\CommandController
{

    /**
     * @Flow\Inject()
     * @var MailUtility
     */
    protected $mailUtility;

    /**
     * @Flow\InjectConfiguration(package="KayStrobach.Custom", path="MailUtility")
     * @var array
     */
    protected $settings = array();

    /**
     * An example command
     *
     * The comment of this command method is also used for TYPO3 Flow's help screens. The first line should give a very short
     * summary about what the command does. Then, after an empty line, you should explain in more detail what the command
     * does. You might also give some usage example.
     *
     * It is important to document the parameters with param tags, because that information will also appear in the help
     * screen.
     *
     * @param string $recipientMail
     * @param string $templateFile
     * @return void
     * @throws \Neos\FluidAdaptor\View\Exception\InvalidSectionException
     */
    public function sendCommand(
        $recipientMail,
        $templateFile = 'resource://KayStrobach.Custom/Private/Templates/Mail/Default.html'
    ) {
        print_r($this->settings);

        $renderedMailSubject = 'SOME SUBJECT';
        $renderedMailContentHtml = 'SOME HTML';
        $renderedMailContentText = 'SOME TEXT';

        $mailUtility = new MailUtility();
        $mailUtility->send(
            $recipientMail,
            $templateFile,
            [
                'values' => []
            ]
        );
    }
}
