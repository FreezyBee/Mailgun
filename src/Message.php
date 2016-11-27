<?php

namespace FreezyBee\Mailgun;

/**
 * Class Message
 * @package FreezyBee\Mailgun
 */
class Message extends \Nette\Mail\Message
{
    /** @var string */
    private $from;

    /** @var string[] */
    private $to = [];

    /** @var string[] */
    private $cc = [];

    /** @var string[] */
    private $bcc = [];

    /** @var Attachment[] */
    private $attachments = [];

    /**
     * @param string $email
     * @param null $name
     * @return Message
     */
    public function setFrom($email, $name = null)
    {
        $this->from = $email;
        parent::setFrom($email, $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $email
     * @param null $name
     * @return Message
     */
    public function addTo($email, $name = null)
    {
        $this->to[] = $email;
        parent::addTo($email, $name);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTo()
    {
        return array_unique($this->to);
    }

    /**
     * @param string $email
     * @param null $name
     * @return Message
     */
    public function addCc($email, $name = null)
    {
        $this->cc[] = $email;
        parent::addCc($email, $name);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getCc()
    {
        return array_unique($this->cc);
    }

    /**
     * @param string $email
     * @param null $name
     * @return Message
     */
    public function addBcc($email, $name = null)
    {
        $this->bcc = $email;
        parent::addBcc($email, $name);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getBcc()
    {
        return array_unique($this->bcc);
    }

    /**
     * @param $file
     * @param null $content
     * @param null $contentType
     * @return Message
     */
    public function addAttachment($file, $content = null, $contentType = null)
    {
        $this->attachments[] = new Attachment($file);
        parent::addAttachment($file, $content, $contentType);
        return $this;
    }

    /**
     * @return Attachment[]
     */
    public function getMailgunAttachments()
    {
        return $this->attachments;
    }
}
