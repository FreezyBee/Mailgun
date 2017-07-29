<?php
declare(strict_types=1);

namespace FreezyBee\Mailgun;

use Nette\InvalidArgumentException;
use Nette\Mail\MimePart;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class Message extends \Nette\Mail\Message
{
    /** @var Attachment[] */
    private $mailgunAttachment = [];

    /**
     * @param string $file
     * @param string $content
     * @param string $contentType
     * @return MimePart
     * @throws InvalidArgumentException
     */
    public function addAttachment($file, $content = null, $contentType = null): MimePart
    {
        if (!is_string($file) || !file_exists($file)) {
            throw new InvalidArgumentException('Parameter $file must be valid path to file');
        }

        $this->mailgunAttachment[] = new Attachment($file);
        return parent::addAttachment($file, $content, $contentType);
    }

    /**
     * @return Attachment[]
     */
    public function getMailgunAttachments(): array
    {
        return $this->mailgunAttachment;
    }
}
