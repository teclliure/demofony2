<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Newsletter;
use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\UserBundle\Entity\User;
use Demofony2\UserBundle\Repository\UserRepository;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Hip\MandrillBundle\Dispatcher as MandrillDispatcher;
use Hip\MandrillBundle\Message;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


/**
 * MailManager
 */
class MailManager implements MailerInterface
{
    protected $mandrill;
    protected $router;
    protected $emailFrom;
    protected $templating;
    protected $parameters;
    protected $userRepository;

    /**
     * @param MandrillDispatcher $md
     * @param RouterInterface    $router
     * @param                    $emailFrom
     * @param EngineInterface    $templating
     * @param array              $parameters
     * @param UserRepository     $userRepository
     */
    public function __construct(MandrillDispatcher $md, RouterInterface $router, $emailFrom, EngineInterface $templating, array $parameters, UserRepository $userRepository)
    {
        $this->mandrill = $md;
        $this->router = $router;
        $this->emailFrom = $emailFrom;
        $this->templating = $templating;
        $this->parameters = $parameters;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Suggestion $suggestion
     */
    public function notifyNewSuggestionCreated(Suggestion $suggestion)
    {
        $from = 'notifications@demofony2.com';
        $to = 'contact@demofony2.com';
        $subject = 'Nueva sugerencia enviada';
        $body = 'Nueva sugerencia enviada';

        $message = $this->createMandrillMessage($from , $body, $subject);
        $message->addTo($to);
        $this->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' =>  $url
        ));
        list($subject, $body) = $this->getSubjectAndBodyFromFOSUserTemplate($rendered);
        $from = key($this->parameters['from_email']['confirmation']);
        $fromName = current($this->parameters['from_email']['confirmation']);
        $message = $this->createMandrillMessage($from , $body, $subject, $fromName);
        $message->addTo($user->getEmail());
        $this->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        list($subject, $body) = $this->getSubjectAndBodyFromFOSUserTemplate($rendered);
        $from = key($this->parameters['from_email']['resetting']);
        $fromName = current($this->parameters['from_email']['resetting']);
        $message = $this->createMandrillMessage($from , $body, $subject, $fromName);
        $message->addTo($user->getEmail());
        $this->send($message);
    }

    public function sendNewsletter(Newsletter $newsletter)
    {
        $message = $this->getNewsletterMessage($newsletter);
        $emails = $this->userRepository->getEmailsNewsletterSubscribed();

        foreach ($emails as $email) {
            $message->addTo($email['email'], $email['name'], 'bcc');
        }
        $message->setTrackClicks(true);

        $this->send($message, false);
    }

    public function sendNewsletterTest(Newsletter $newsletter, User $user)
    {
        $message = $this->getNewsletterMessage($newsletter);
        $message->addTo($user->getEmail(), '', 'to');
        $this->send($message, true);
    }

    protected function getNewsletterMessage(Newsletter $newsletter)
    {
        $from = $this->emailFrom;
        $fromName = 'Newsletter';
        $body='body newsletter';
        $subject = 'subject newsletter';
        $message = $this->createMandrillMessage($from, $body, $subject, $fromName);

        return $message;
    }

    /**
     * @param        $from
     * @param        $body
     * @param        $subject
     * @param string $fromName
     *
     * @return Message
     */
    protected function createMandrillMessage($from, $body, $subject, $fromName = 'Demofony2')
    {
        $message = new Message();

        $message
            ->setFromEmail($from)
            ->setFromName($fromName)
            ->setSubject($subject)
            ->setHtml($body);

        return $message;
    }

    /**
     * @param Message $message
     * @param bool    $isImportant
     *
     * @return array|bool
     */
    public function send(Message $message, $isImportant = true)
    {
        if ($isImportant) {
            $message->isImportant();
        }

        return $this->mandrill->send($message);
    }

    private function getSubjectAndBodyFromFOSUserTemplate($rendered)
    {
        $renderedLines = explode("\n", trim($rendered));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        return array($subject, $body);
    }
}
