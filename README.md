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

`HttpAdapter`s are responsible to get data from DigitalOcean's API.

Currently, there are the following adapters:

* `CurlHttpAdapter` to use [cURL](http://php.net/manual/book.curl.php).
* `BuzzHttpAdapter` to use [Buzz](https://github.com/kriswallsmith/Buzz), a lightweight PHP 5.3 library for
issuing HTTP requests.
* `GuzzleHttpAdapter` to use [Guzzle](https://github.com/guzzle/guzzle), PHP 5.3+ HTTP client and framework
for building RESTful web service clients.

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

```php
...
try {
    echo $digitalOcean->droplet()->showAllActive()->status; // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

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

```php
...
try {
    echo $digitalOcean->region()->getAll()->status; // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

* `getAll()`: returns all the available regions within the Digital Ocean cloud.

### Images ###

```php
...
try {
    echo $digitalOcean->image()->getAll()->status; // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

* `getAll()`: returns all the available images that can be accessed by your client ID. You will have access to
all public images by default, and any snapshots or backups that you have created in your own account.
* `getMyImages()`: returns all your images.
* `getGlobal()`: returns all global images.
* `show($id)`: displays the attributes of an image.
* `destroy($id)`: destroys an image. There is no way to restore a deleted image so be careful and ensure your data
is properly backed up.

### SSH Keys ###

```php
...
try {
    echo $digitalOcean->sshKeys()->getAll()->status; // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

* `getAll()`: returns all the available public SSH keys in your account that can be added to a droplet.
* `show($id)`: shows a specific public SSH key in your account that can be added to a droplet.
* `add($argument)`: adds a new public SSH key to your account. The argument should be anarray with **name** and
**ssh_key_pub** keys.
* `edit($argument)`: edits an existing public SSH key in your account. The argument should be an array with
**ssh_key_pub** key.
* `destroy($id)`: deletes the SSH key from your account.

### Sizes ###

```php
...
try {
    echo $digitalOcean->size()->getAll()->status; // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

* `getAll()`: returns all the available sizes that can be used to create a droplet.


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