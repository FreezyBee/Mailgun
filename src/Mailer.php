<?php

namespace FreezyBee\Mailgun;

use Mailgun\Mailgun;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

/**
 * Class Mailer
 * @package FreezyBee\Mailgun
 */
class Mailer implements IMailer
{
    /** @var array */
    private $config;

    /** @var Mailgun */
    private $mailgun;

    /**
     * MailGunMailer constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param Message $mail
     */
    public function send(Message $mail)
    {
        if ($mail instanceof \FreezyBee\Mailgun\Message) {
            $this->sendMailgunMessage($mail);
        } elseif ($mail->getAttachments()) {
            throw new \InvalidArgumentException(
                'If you wanna send attachment, parameter $mail must be ' . \FreezyBee\Mailgun\Message::class . ' type'
            );
        } else {
            $this->sendNetteMessage($mail);
        }
    }

    /**
     * @param Message $mail
     */
    protected function sendNetteMessage(Message $mail)
    {
        $mailgun = $this->getMailgun();
        $messageBuilder = $mailgun->MessageBuilder();

        $messageBuilder->setFromAddress(key($mail->getHeader('From')));
        $messageBuilder->setSubject($mail->getSubject());

        if ($mail->getHtmlBody()) {
            $messageBuilder->setHtmlBody($mail->getHtmlBody());
        } else {
            $messageBuilder->setTextBody($mail->getBody());
        }

        foreach ($mail->getHeader('To') as $email => $null) {
            $messageBuilder->addToRecipient($email, []);
        }

        foreach ($mail->getHeader('Cc') ?: [] as $email => $null) {
            $messageBuilder->addCcRecipient($email, []);
        }

        foreach ($mail->getHeader('Bcc') ?: [] as $email => $null) {
            $messageBuilder->addBccRecipient($email, []);
        }

        $mailgun->sendMessage($this->config['domain'], $messageBuilder->getMessage());
    }

    /**
     * @param \FreezyBee\Mailgun\Message $mail
     */
    protected function sendMailgunMessage(\FreezyBee\Mailgun\Message $mail)
    {
        $mailgun = $this->getMailgun();
        $messageBuilder = $mailgun->MessageBuilder();

        $messageBuilder->setFromAddress($mail->getFrom());
        $messageBuilder->setSubject($mail->getSubject());

        if ($mail->getHtmlBody()) {
            $messageBuilder->setHtmlBody($mail->getHtmlBody());
        } else {
            $messageBuilder->setTextBody($mail->getBody());
        }

        foreach ($mail->getMailgunAttachments() as $attachment) {
            $messageBuilder->addAttachment($attachment->getPath(), $attachment->getName());
        }

        foreach ($mail->getTo() as $email) {
            $messageBuilder->addToRecipient($email, []);
        }

        foreach ($mail->getCc() as $email => $null) {
            $messageBuilder->addCcRecipient($email, []);
        }

        foreach ($mail->getBcc() as $email => $null) {
            $messageBuilder->addBccRecipient($email, []);
        }

        $mailgun->post(
            $this->config['domain'] . '/messages',
            $messageBuilder->getMessage(),
            $messageBuilder->getFiles()
        );
    }

    /**
     * @return Mailgun
     */
    protected function getMailgun()
    {
        if (!$this->mailgun) {
            $this->mailgun = new Mailgun($this->config['key']);
        }

        return $this->mailgun;
    }
}
