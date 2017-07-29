<?php
declare(strict_types=1);

namespace FreezyBee\Mailgun;

use Nette\SmartObject;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class Attachment
{
    use SmartObject;

    /*** @var string */
    private $path;

    /**
     * Attachment constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
