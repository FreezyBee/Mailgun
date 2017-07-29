<?php
declare(strict_types=1);

namespace FreezyBee\Mailgun;

use Mailgun\Mailgun;
use Nette\InvalidArgumentException;
use Nette\Mail\IMailer;
use Nette\Mail\Message as NetteMessage;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
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
     * @param NetteMessage $mail
     */
    public function send(NetteMessage $mail)
    {
        $params = [];

        $from = $mail->getHeader('From');
        $params['from'] = $this->convertAddress(key($from), reset($from));

        $params['subject'] = $mail->getSubject();

        if ($mail->getHtmlBody()) {
            $params['html'] = $mail->getHtmlBody();
        } else {
            $params['text'] = $mail->getBody();
        }

        foreach ($mail->getHeader('To') ?: [] as $email => $name) {
            $params['to'][] = $this->convertAddress($email, $name);
        }

        foreach ($mail->getHeader('Cc') ?: [] as $email => $name) {
            $params['cc'][] = $this->convertAddress($email, $name);
        }

        foreach ($mail->getHeader('Bcc') ?: [] as $email => $name) {
            $params['bcc'][] = $this->convertAddress($email, $name);
        }

        if ($mail instanceof Message) {
            /** @var Message $mail */
            foreach ($mail->getMailgunAttachments() as $attachment) {
                $params['attachment'][] = ['filePath' => $attachment->getPath()];
            }
        } elseif ($mail->getAttachments()) {
            throw new InvalidArgumentException(
                'If you wanna send attachment, parameter $mail must be ' . Message::class . ' type'
            );
        }

        $this->getMailgun()->messages()->send($this->config['domain'], $params);
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return string
     */
    protected function convertAddress(string $email, string $name = null): string
    {
        return $name === null ? $email : "$name <$email>";
    }

    /**
     * @return Mailgun
     */
    protected function getMailgun(): Mailgun
    {
        if (!$this->mailgun) {
            $this->mailgun = Mailgun::create($this->config['key']);
        }

        return $this->mailgun;
    }
}
