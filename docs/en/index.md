Quickstart
==========


Installation
------------

The best way to install FreezyBee/Mailgun is using  [Composer](http://getcomposer.org/):

```sh
$ composer require freezy-bee/mailgun
```

With Nette `2.3` and newer, you can enable the extension using your neon config.

```yml
extensions:
	mailgun: FreezyBee\Mailgun\DI\MailgunExtension
```

Full configuration
------------------

```yml
mailgun:
    domain: example.com # required
    key: key # required
    autowired: true # default false - if you set it true -> it force autowired all IMailer interfaces to Mailgun
```
