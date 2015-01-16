<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Suggestion;
use Symfony\Component\Routing\RouterInterface;

/**
 * MailManager
 * @package Demofony2\AppBundle\Manager
 */
class MailManager
{
    protected $mailer;
    protected $router;
    protected $emailFrom;

    /**
     * @param \Swift_Mailer   $mailer
     * @param RouterInterface $router
     * @param                 $emailFrom
     */
    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->emailFrom = $emailFrom;
    }

    public function notifyNewSuggestionCreated(Suggestion $suggestion)
    {
        $from = 'notifications@demofony2.com';
        $to = 'contact@demofony2.com';
        $subject = 'Nueva sugerencia enviada';
        $body = 'Nueva sugerencia enviada';

        $this->send($from, $to, $body, $subject);
    }

    public function send($from, $to, $body, $subject, $html = false)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($from => 'Demofony2'))
            ->setTo($to);

        if ($html) {
            $message->setBody($body, 'text/html');
        } else {
            $message->setBody($body);
        }

        return $this->mailer->send($message);
    }
}
