<?php

namespace FreezyBee\Mailgun;

use Mailgun\Mailgun;
use Nette\InvalidArgumentException;
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
        $mailgun = $this->getMailgun();
        $messageBuilder = $mailgun->MessageBuilder();

        $from = $mail->getHeader('From');
        $messageBuilder->setFromAddress($this->convertAddress(key($from), reset($from)));
        $messageBuilder->setSubject($mail->getSubject());

        if ($mail->getHtmlBody()) {
            $messageBuilder->setHtmlBody($mail->getHtmlBody());
        } else {
            $messageBuilder->setTextBody($mail->getBody());
        }

        foreach ($mail->getHeader('To') ?: [] as $email => $name) {
            $messageBuilder->addToRecipient($this->convertAddress($email, $name), []);
        }

        foreach ($mail->getHeader('Cc') ?: [] as $email => $name) {
            $messageBuilder->addCcRecipient($this->convertAddress($email, $name), []);
        }

        foreach ($mail->getHeader('Bcc') ?: [] as $email => $name) {
            $messageBuilder->addBccRecipient($this->convertAddress($email, $name), []);
        }

        if ($mail instanceof \FreezyBee\Mailgun\Message) {
            /** @var \FreezyBee\Mailgun\Message $mail */
            foreach ($mail->getMailgunAttachments() as $attachment) {
                $messageBuilder->addAttachment($attachment->getPath());
            }
        } elseif ($mail->getAttachments()) {
            throw new InvalidArgumentException(
                'If you wanna send attachment, parameter $mail must be ' . \FreezyBee\Mailgun\Message::class . ' type'
            );
        }

        $mailgun->post(
            $this->config['domain'] . '/messages',
            $messageBuilder->getMessage(),
            $messageBuilder->getFiles()
        );
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return string
     */
    protected function convertAddress(string $email, string $name = null)
    {
        return $name === null ? $email : "$name <$email>";
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
