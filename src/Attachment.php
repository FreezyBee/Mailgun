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

    /** @var string */
    private $name;

    /**
     * Attachment constructor.
     * @param string $path
     * @param string|null $name
     */
    public function __construct(string $path, string $name = null)
    {
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
