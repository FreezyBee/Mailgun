<?php
declare(strict_types=1);

namespace FreezyBee\Mailgun\DI;

use FreezyBee\Mailgun\Mailer;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
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
        $config = $this->validateConfig($this->defaults);

        Validators::assertField($config, 'domain', 'string');
        Validators::assertField($config, 'key', 'string');
        Validators::assertField($config, 'autowired', 'bool');

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
