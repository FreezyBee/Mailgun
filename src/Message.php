<?php

namespace FreezyBee\Mailgun;

use Nette\InvalidArgumentException;

/**
 * Class Message
 * @package FreezyBee\Mailgun
 */
class Message extends \Nette\Mail\Message
{
    /** @var Attachment[] */
    private $attachments = [];

    /**
     * @param $file
     * @param null $content
     * @param null $contentType
     * @return \Nette\Mail\MimePart
     */
    public function addAttachment($file, $content = null, $contentType = null)
    {
        if (!is_string($file) || !file_exists($file)) {
            throw new InvalidArgumentException('Parameter $file must be valid path to file');
        }

        $this->attachments[] = new Attachment($file);
        return parent::addAttachment($file, $content, $contentType);
    }

    /**
     * @return Attachment[]
     */
    public function getMailgunAttachments()
    {
        return $this->attachments;
    }
}
