<?php

namespace KayStrobach\Custom\Utility;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "KayStrobach.Custom".    *
 *                                                                        *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Psr\Log\LoggerInterface;

/**
 * Objekt zum erzeugen von Studenten!
 *
 * @Flow\Scope("singleton")
 */

class MailUtility
{
    /**
     * The current view, as resolved by resolveView()
     *
     * @Flow\Inject
     * @var \Neos\FluidAdaptor\View\StandaloneView
     * @api
     */
    protected $view;

    /**
     * @Flow\Inject
     * @var \Neos\SwiftMailer\TransportFactory
     * @api
     */
    protected $mailerTransportFactory;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Configuration\ConfigurationManager
     */
    protected $configurationManager;

    /**
     * @Flow\InjectConfiguration(package="KayStrobach.Custom", path="MailUtility")
     * @var array
     */
    protected $settings = [];

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @FLow\Inject
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Hier wird die Mail an das Sendesystem übergeben.
     *
     * Das Template sollte wie folgt aussehen:
     *
     * <f:section name="subject">
     *     Betreff
     * </f:section>
     * <f:section name="text">
     *     Mailtext ohne html
     * </f:section>
     * <f:section name="html">
     *     html mail
     * </f:section>
     *
     * @param string|array $recipientMail
     * @param string $templateFilePath Pfad zum Fluid Template
     * @param array $values array mit Schlüssel => Objekt / Wert Zuordungen
     * @param string $replyTo
     * @param array $attachments
     * @throws \Neos\FluidAdaptor\View\Exception\InvalidSectionException
     */
    public function send($recipientMail, $templateFilePath, $values = array(), $replyTo = null, array $attachments = [])
    {
        /** @var $mail \Neos\SwiftMailer\Message() */
        $this->view->setTemplatePathAndFilename($templateFilePath);
        $this->view->assignMultiple($values);

        $renderedMailSubject     = trim($this->view->renderSection('subject', [], true));
        $renderedMailContentText = trim($this->view->renderSection('text', [], true));
        $renderedMailContentHtml = trim($this->view->renderSection('html', [], true));

        $mail = new \Neos\SwiftMailer\Message();
        $mail
            ->setFrom($this->settings['from'])
            ->setReplyTo($replyTo ? $replyTo : $this->settings['reply-to'])
            ->setTo($recipientMail)
            ->setSubject(trim(htmlspecialchars_decode($renderedMailSubject)))
            ->setPriority(1);
        if ($renderedMailContentHtml !== '') {
            $mail->addPart($renderedMailContentHtml, 'text/html', 'utf-8');
        }
        if ($renderedMailContentText !== '') {
            $mail->addPart($renderedMailContentText, 'text/plain', 'utf-8');
        }
        foreach ($attachments as $attachment) {
            if ($attachment instanceof PersistentResource) {
                $mail->attach(
                    new \Swift_Attachment(
                        stream_get_contents($attachment->getStream()),
                        $attachment->getFilename(),
                        $attachment->getMediaType()
                    )
                );
            }
        }

        $this->logger->debug(
            'Send Message with MailUtility',
            [
                'from' => $this->settings['from'],
                'recipient' => $recipientMail,
                'templateFile' => $templateFilePath,
                'values' => $values,
                'replyTo' => $replyTo ? $replyTo : $this->settings['reply-to']

            ]
        );
        $mail->send();
    }
}
