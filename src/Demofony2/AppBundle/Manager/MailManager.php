<?php

namespace Demofony2\AppBundle\Manager;

use Demofony2\AppBundle\Entity\Newsletter;
use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\Suggestion;
use Demofony2\UserBundle\Entity\User;
use Demofony2\UserBundle\Repository\UserRepository;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Hip\MandrillBundle\Dispatcher as MandrillDispatcher;
use Hip\MandrillBundle\Message;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * MailManager.
 */
class MailManager implements MailerInterface
{
    protected $mandrill;
    protected $router;
    protected $emailFrom;
    protected $templating;
    protected $parameters;
    protected $userRepository;
    protected $translator;

    /**
     * @param MandrillDispatcher  $md
     * @param RouterInterface     $router
     * @param                     $emailFrom
     * @param EngineInterface     $templating
     * @param array               $parameters
     * @param UserRepository      $userRepository
     * @param TranslatorInterface $translatorInterface
     */
    public function __construct(
        MandrillDispatcher $md,
        RouterInterface $router,
        $emailFrom,
        EngineInterface $templating,
        array $parameters,
        UserRepository $userRepository,
        TranslatorInterface $translatorInterface
    ) {
        $this->mandrill = $md;
        $this->router = $router;
        $this->emailFrom = $emailFrom;
        $this->templating = $templating;
        $this->parameters = $parameters;
        $this->userRepository = $userRepository;
        $this->translator = $translatorInterface;
    }

    /**
     * @param Suggestion $suggestion
     */
    public function notifyNewSuggestionCreated(Suggestion $suggestion)
    {
        $from = 'notifications@demofony2.com';
        $to = 'contact@demofony2.com';
        $subject = 'Nova sugerència enviada';
        $body = $this->templating->render(
            ':Mail:new_suggestion.html.twig',
            array(
                'suggestion' => $suggestion,
            )
        );

        $message = $this->createMandrillMessage($from, $body, $subject);
        $message->addTo($to);
        $this->send($message);
    }

    /**
     * @param Proposal $proposal proposal object
     */
    public function notifyNewProposalCreated(Proposal $proposal)
    {
        $from = 'notifications@demofony2.com';
        $to = 'contact@demofony2.com';
        $subject = 'Nova proposta enviada';
        $body = $this->templating->render(
            ':Mail:new_proposal.html.twig',
            array(
                'proposal' => $proposal,
            )
        );

        $message = $this->createMandrillMessage($from, $body, $subject);
        $message->addTo($to);
        $this->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate(
            'fos_user_registration_confirm',
            array('token' => $user->getConfirmationToken()),
            true
        );
        $body = $this->templating->render(
            $template,
            array(
                'user' => $user,
                'confirmationUrl' => $url,
            )
        );
        $subject = $this->translator->trans('confirmation.email.subject', array(), 'FOSUserBundle');
        $from = key($this->parameters['from_email']['confirmation']);
        $fromName = current($this->parameters['from_email']['confirmation']);
        $message = $this->createMandrillMessage($from, $body, $subject, $fromName);
        $message->addTo($user->getEmail());
        $this->send($message);
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate(
            'fos_user_resetting_reset',
            array('token' => $user->getConfirmationToken()),
            true
        );
        $body = $this->templating->render(
            $template,
            array(
                'user' => $user,
                'confirmationUrl' => $url,
            )
        );
        $subject = $this->translator->trans('resetting.email.subject', array(), 'FOSUserBundle');
        $from = key($this->parameters['from_email']['resetting']);
        $fromName = current($this->parameters['from_email']['resetting']);
        $message = $this->createMandrillMessage($from, $body, $subject, $fromName);
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
        $body = $this->templating->render(
            ':Mail:newsletter.html.twig',
            array(
            )
        );
        $from = $this->emailFrom;
        $fromName = 'Newsletter';
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
}
