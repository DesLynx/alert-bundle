# AlertBundle

[![Latest stable version](https://img.shields.io/packagist/v/DesLynx/alert-bundle.svg?style=flat-square)](https://packagist.org/packages/deslynx/alert-bundle)
[![License](http://img.shields.io/packagist/l/DesLynx/alert-bundle.svg?style=flat-square)](https://packagist.org/packages/deslynx/alert-bundle)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat-square)](https://phpstan.org/)
[![PHPStan Level max](https://img.shields.io/badge/PHPStan-Level_max-brightgreen.svg?style=flat-square)](https://phpstan.org/)

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
composer require deslynx/alert-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require deslynx/alert-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    DesLynx\AlertBundle\DesLynxAlertBundle::class => ['all' => true],
];
```

Configuration
-------------

```yaml
# /config/packeges/deslynx_alert.yaml
deslynx_alert:

    # The sender of the email, like "Name <email@address.com>" or a simple email.
    from:                 ~ # Required

    # A list of always added recipients for the email, like "Name <email@address.com>" or a simple email.
    to:                   [] # Required

    # Your project name. Included in the email subject
    projectName:          'My project'

    # If you want to use a custom email transport set this to the name of the transport.
    transport:            null
```

Usage (service)
-----

This bundle provide a single service for sending an alert email which you can autowire by using the `AlertHelper` type-hint:
```php
// src/Controller/SomeController.php

use DesLynx\AlertBundle\Service\AlertHelper;
//...

class SomeController
{
    public function index(AlertHelper $alertHelper) {
        $alertHelper->sendAlert(
            'A significant subject.',
            'A significant message. Either text or HTML.',
            false, // Optional. Set to true if the message is HTML
            ['email1@address.com', 'email2@address.com' /*, ...*/] // Optional. A list of recipients to add in cc in addition to the globally defined recipients (see configuration)
         );
    }    
}
```

Usage (Monolog Handler)
-----

This bundle provide a Monolog Handler using the `AlertHelper` service. It allows to add a monolog handler config to send an alert email with the full log stack on every critical error happening in the project. 

Just add this to your monolog config:

```yaml
# config/packages/monolog.yaml
monolog:
  handlers:
    # ...
    deslynx_critical:
      type: fingers_crossed
      action_level: critical
      handler: deslynx_deduplicated
    deslynx_deduplicated:
      type: deduplication
      handler: deslynx_alert_mailer
    deslynx_alert_mailer:
      type: service
      id: DesLynx\AlertBundle\Monolog\Handler\AlertMailerHandler
      level: debug
```