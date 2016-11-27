<?php

namespace FreezyBee\Mailgun;

use Nette\SmartObject;

/**
 * Class Attachment
 * @package FreezyBee\Mailgun
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
    public function getPath()
    {
        return $this->path;
    }
}
