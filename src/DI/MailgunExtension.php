<?php

namespace FreezyBee\Mailgun\DI;

use FreezyBee\Mailgun\Mailer;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;

/**
 * Class MailgunExtension
 * @package FreezyBee\Mailgun\DI
 */
class MailgunExtension extends CompilerExtension
{
    /**
     * @var array
     */
    private $defaults = [
        'domain' => null,
        'key' => null,
        'autowired' => false
    ];

    /**
     *
     */
    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);

        if (!$config['domain'] || !$config['key']) {
            throw new InvalidArgumentException('Required mailgun parameter domain or key missing');
        }

        $builder = $this->getContainerBuilder();

        $mailer = $builder->addDefinition($this->prefix('mailer'))
            ->setClass(Mailer::class)
            ->setArguments([$config])
            ->setAutowired(false);

        if ($config['autowired']) {
            $mailer->setAutowired(true);
            $builder->removeAlias('mail.mailer');
            $builder->removeDefinition('mail.mailer');
            $builder->addAlias('mail.mailer', $this->prefix('mailer'));
        }
    }
}
