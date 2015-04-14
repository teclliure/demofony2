<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Suggestion;
use Symfony\Component\Routing\RouterInterface;
use Hip\MandrillBundle\Dispatcher as MandrillDispatcher;
use Hip\MandrillBundle\Message;

/**
 * MailManager
 * @package Demofony2\AppBundle\Manager
 */
class MailManager
{
    protected $mandrill;
    protected $router;
    protected $emailFrom;

    /**
     * @param MandrillDispatcher $md
     * @param RouterInterface    $router
     * @param                    $emailFrom
     */
    public function __construct(MandrillDispatcher $md, RouterInterface $router, $emailFrom)
    {
        $this->mandrill = $md;
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

    public function send($from, $to, $body, $subject, $fromName = 'Demofony2', $isImportant = true)
    {
        $message = new Message();


        $message
            ->setFromEmail($from)
            ->setFromName($fromName)
            ->addTo($to)
            ->setSubject($subject)
            ->setHtml($body);

        if ($isImportant) {
            $message->isImportant();
        }

        return $this->mandrill->send($message);
    }
}
