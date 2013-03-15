DigitalOcean
============

This PHP 5.3+ library helps you to interact with the [DigitalOcean](https://www.digitalocean.com/)
[API](https://www.digitalocean.com/api).

[![Build Status](https://secure.travis-ci.org/toin0u/DigitalOcean.png)](http://travis-ci.org/toin0u/DigitalOcean)
[![project status](http://stillmaintained.com/toin0u/DigitalOcean.png)](http://stillmaintained.com/toin0u/DigitalOcean)

> DigitalOcean is **built for Developers**, helps to **get things done faster** and to
> **deploy an SSD cloud server** in less than 55 seconds with a **dedicated IP** and **root access**.
> [Read more](https://www.digitalocean.com/features).


Installation
------------

This library can be found on [Packagist](https://packagist.org/packages/toin0u/digitalocean).
The recommended way to install this is through [composer](http://getcomposer.org).

Run these commands to install composer, the library and its dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require toin0u/digitalocean:@stable
```

Now you can add the autoloader, and you will have access to the library:

```php
<?php

require 'vendor/autoload.php';
```

If you don't use neither **Composer** nor a _ClassLoader_ in your application, just require the provided autoloader:

```php
<?php

require_once 'src/autoload.php';
```


Usage
-----

```php

require 'vendor/autoload.php';

use DigitalOcean\DigitalOcean;
use DigitalOcean\HttpAdapter\CurlHttpAdapter;

$digitalOcean = new DigitalOcean('YOUR_CLIENT_ID', 'YOUR_API_KEY', new CurlHttpAdapter());
...
```


API
---

### Droplets ###

* `showAllActive()`: returns all active droplets that are currently running in your account.
* `show($id)`: returns full information for a specific droplet.
* `create($argument)`: creates a new droplet. The argument should be an **array** with 4 required keys: **name**,
**sized_id**, **image_id** and **region_id**. **ssh_key_ids** key is optional.
* `reboot($id)`: reboots a droplet.
* `powerCycle($id)`: power cycles a droplet.
* `shutdown($id)`: shutdowns a running droplet.
* `powerOn($id)`: powerons a powered off droplet.
* `powerOff($id)`: poweroffs a running droplet.
* `resetRootPassword($id)`: resets the root password for a droplet.
* `resize($id, $argument)`: resizes a specific droplet to a different size. The argument should be an array with
**size_id** key.
* `snapshot($id, $argument)`: takes a snapshot of the running droplet, which can later be restored or used to create
a new droplet from the same image. The argument can be an empty array or an array with **name** key.
* `restore($id, $argument)`: restores a droplet with a previous image or snapshot. The argument should be an array with
**image_id** key.
* `rebuild($id, $argument)`: reinstalls a droplet with a default image. The argument should be an array with
**image_id** key.
* `enableAutomaticBackups($id)`: enables automatic backups which run in the background daily to backup your droplet's
data.
* `disableAutomaticBackups($id)`: disables automatic backups from running to backup your droplet's data.
* `destroy($id)`: destroys one of your droplets - this is irreversible !

### Regions ###

* `regions()`: returns all the available regions within the Digital Ocean cloud.

### Images ###

WIP

### SSH Keys ###

WIP

### Sizes ###

WIP


Unit Tests
----------

To run unit tests, you'll need the `cURL` extension and a set of dependencies, you can install them using Composer:

```bash
$ php composer.phar install --dev
```

Once installed, just launch the following command:

```bash
$ phpunit --coverage-text
```

You'll obtain some skipped unit tests due to the need of API keys.

Rename the `phpunit.xml.dist` file to `phpunit.xml`, then uncomment the following lines and add your own API keys:

```xml
<php>
    <!-- <server name="CLIENT_ID" value="YOUR_CLIENT_ID" /> -->
    <!-- <server name="API_KEY" value="YOUR_API_KEY" /> -->
</php>
```


Contributing
------------

Please see [CONTRIBUTING](https://github.com/toin0u/DigitalOcean/blob/master/CONTRIBUTING.md) for details.


Credits
-------

* [Antoine Corcy](https://twitter.com/toin0u) - Owner
* [All contributors](https://github.com/toin0u/DigitalOcean/contributors)


Changelog
---------

[See the changelog file](https://github.com/toin0u/DigitalOcean/blob/master/CHANGELOG.md)


Support
-------

[Please open an issues in github](https://github.com/toin0u/DigitalOcean/issues)


License
-------

Geotools is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/DigitalOcean/blob/master/LICENSE) file for details.