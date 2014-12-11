<?php

namespace Demofony2\AppBundle\Manager;

use Symfony\Component\Routing\Router;

/**
 * MailManager
 *
 * @package Demofony2\AppBundle\Manager
 */
class MailManager
{
    protected $mailer;
    protected $router;
    protected $emailFrom;

    public function __construct(\Swift_Mailer $mailer, $message, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->message = $message;
        $this->emailFrom = $emailFrom;
    }


    public function setRouter(Router $router)
    {
        return $this->router = $router;
    }

    public function send($from, $to, $body, $subject, $html = true)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($from => 'Demofony2'))
            ->setTo($to);

        if ($html) {
            $message->setBody($body, 'text/html');
        }else{
            $message->setBody($body);
        }

        return $this->mailer->send($message);
    }
}
