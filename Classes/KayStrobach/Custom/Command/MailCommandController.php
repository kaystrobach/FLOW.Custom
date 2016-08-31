<?php
namespace KayStrobach\Custom\Command;

/*
 * This file is part of the KayStrobach.Custom package.
 */

use KayStrobach\Custom\Utility\MailUtility;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class MailCommandController extends \TYPO3\Flow\Cli\CommandController
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
     * @param string $requiredArgument This argument is required
     * @param string $optionalArgument This argument is optional
     * @return void
     */
    public function sendCommand($recipientMail, $replyTo = NULL)
    {
        print_r($this->settings);

        $renderedMailSubject = 'SOME SUBJECT';
        $renderedMailContentHtml = 'SOME HTML';
        $renderedMailContentText = 'SOME TEXT';
        
        $mail = new \TYPO3\SwiftMailer\Message();
        $mail
            ->setFrom($this->settings['from'])
            ->setReplyTo($replyTo ? $replyTo : $this->settings['reply-to'])
            ->setTo($recipientMail)
            ->setSubject($renderedMailSubject)
            ->setPriority(1);
        if ($renderedMailContentHtml !== '') {
            $mail->addPart($renderedMailContentHtml, 'text/html', 'utf-8');
        }
        if ($renderedMailContentText !== '') {
            $mail->addPart($renderedMailContentText, 'text/plain', 'utf-8');
        }
        $mail->send();
    }
}
