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

You need an `HttpAdapter` which is responsible to get data from DigitalOcean's RESTfull API.
You can provide your own adapter by implementing `\DigitalOcean\HttpAdapter\HttpAdapterInterface`.

Currently, there are the following adapters:

* `CurlHttpAdapter` to use [cURL](http://php.net/manual/book.curl.php).
* `BuzzHttpAdapter` to use [Buzz](https://github.com/kriswallsmith/Buzz), a lightweight PHP 5.3 library for
issuing HTTP requests.
* `GuzzleHttpAdapter` to use [Guzzle](https://github.com/guzzle/guzzle), PHP 5.3+ HTTP client and framework
for building RESTful web service clients.
* `ZendHttpAdapter` to use [Zend Http Client](http://framework.zend.com/manual/2.0/en/modules/zend.http.client.html).
* `SocketHttpAdapter` to use a [socket](http://www.php.net/manual/function.fsockopen.php).

```php
require 'vendor/autoload.php';

use DigitalOcean\DigitalOcean;
use DigitalOcean\HttpAdapter\CurlHttpAdapter;

$digitalOcean = new DigitalOcean('YOUR_CLIENT_ID', 'YOUR_API_KEY', new CurlHttpAdapter());
// ...
```


API
---

### Droplets ###

```php
// ...
$droplets = $digitalOcean->droplets();
try {
    // Returns all active droplets that are currently running in your account.
    $allActive = $droplets->showAllActive();
    printf("%s\n", $allActive->status); // OK
    $firstDroplet = $allActive->droplets[0];
    printf("%s\n", $firstDroplet->id); // 12345
    printf("%s\n", $firstDroplet->name); // foobar
    printf("%s\n", $firstDroplet->image_id); // 56789
    printf("%s\n", $firstDroplet->size_id); // 66
    printf("%s\n", $firstDroplet->region_id); // 2
    printf("%s\n", $firstDroplet->backups_active); // 1
    printf("%s\n", $firstDroplet->ip_address); // 127.0.0.1
    printf("%s\n", $firstDroplet->status); // active
    // Returns full information for a specific droplet.
    printf("%s\n", $droplets->show(12345)->droplet->name); // foobar
    // Creates a new droplet. The argument should be an **array** with 4 required keys:
    // name, sized_id, image_id and region_id. ssh_key_ids key is optional but if any it should be a string.
    $createDroplet = $droplets->create(array(
        'name'        => 'my_new_droplet',
        'sized_id'    => 123,
        'image_id'    => 456,
        'region_id'   => 789,
        'ssh_key_ids' => '12,34,56', // 3 ssh keys
    ));
    printf("%s\n", $createDroplet->status); // OK
    printf("%s\n", $createDroplet->droplet->event_id); // 78908
    // Reboots a droplet.
    $rebootDroplet = $droplets->reboot(12345);
    printf("%s, %s\n", $rebootDroplet->status, $rebootDroplet->event_id);
    // Power cycles a droplet.
    $powerCycleDroplet = $droplets->powerCycle(12345);
    printf("%s, %s\n", $powerCycleDroplet->status, $powerCycleDroplet->event_id);
    // Shutdowns a running droplet.
    $shutdownDroplet = $droplets->shutdown(12345);
    printf("%s, %s\n", $shutdownDroplet->status, $shutdownDroplet->event_id);
    // Powerons a powered off droplet.
    $powerOnDroplet = $droplets->powerOn(12345);
    printf("%s, %s\n", $powerOnDroplet->status, $powerOnDroplet->event_id);
    // Poweroffs a running droplet.
    $powerOffDroplet = $droplets->powerOff(12345);
    printf("%s, %s\n", $powerOffDroplet->status, $powerOffDroplet->event_id);
    // Resets the root password for a droplet.
    $resetRootPasswordDroplet = $droplets->resetRootPassword(12345);
    printf("%s, %s\n", $resetRootPasswordDroplet->status, $resetRootPasswordDroplet->event_id);
    // Resizes a specific droplet to a different size. The argument should be an array with size_id key.
    $resetRootPasswordDroplet = $droplets->resize(12345, array('size_id' => 123));
    printf("%s, %s\n", $resetRootPasswordDroplet->status, $resetRootPasswordDroplet->event_id);
    // Takes a snapshot of the running droplet, which can later be restored or used to create a new droplet
    // from the same image. The argument can be an empty array or an array with name key.
    $resetRootPasswordDroplet = $droplets->snapshot(12345, array('name' => 'my_snapshot'));
    printf("%s, %s\n", $resetRootPasswordDroplet->status, $resetRootPasswordDroplet->event_id);
    // Restores a droplet with a previous image or snapshot. The argument should be an array with image_id key.
    $resetRootPasswordDroplet = $droplets->restore(12345, array('image_id' => 123));
    printf("%s, %s\n", $resetRootPasswordDroplet->status, $resetRootPasswordDroplet->event_id);
    // Reinstalls a droplet with a default image. The argument should be an array with image_id key.
    $resetRootPasswordDroplet = $droplets->rebuild(12345, array('image_id' => 123));
    printf("%s, %s\n", $resetRootPasswordDroplet->status, $resetRootPasswordDroplet->event_id);
    // Enables automatic backups which run in the background daily to backup your droplet's data.
    $enableBackupsDroplet = $droplets->enableAutomaticBackups(12345);
    printf("%s, %s\n", $enableBackupsDroplet->status, $enableBackupsDroplet->event_id);
    // Disables automatic backups from running to backup your droplet's data.
    $disableBackupsDroplet = $droplets->disableAutomaticBackups(12345);
    printf("%s, %s\n", $disableBackupsDroplet->status, $disableBackupsDroplet->event_id);
    // Destroys one of your droplets - this is irreversible !
    $destroyDroplet = $droplets->destroy(12345);
    printf("%s, %s\n", $destroyDroplet->status, $destroyDroplet->event_id);
} catch (Exception $e) {
    die($e->getMessage());
}
```

### Regions ###

```php
// ...
$regions = $digitalOcean->regions();
try {
    // Returns all the available regions within the Digital Ocean cloud.
    $allRegions = $regions->getAll();
    printf("%s\n", $allRegions->status); // OK
    $region1 = $allRegions->regions[0];
    printf("%s, %s\n", $region1->id, $region1->name); // 1, New York 1
    $region2 = $allRegions->regions[1];
    printf("%s, %s\n", $region2->id, $region2->name); // 2, Amsterdam 1
} catch (Exception $e) {
    die($e->getMessage());
}
```

### Images ###

```php
// ...
$images = $digitalOcean->images();
try {
    // Returns all the available images that can be accessed by your client ID. You will have access
    // to all public images by default, and any snapshots or backups that you have created in your own account.
    $allImages = $images->getAll();
    printf("%s\n", $allImages->status); // OK
    $firstImage = $allImages->images[0];
    printf("%s\n", $firstImage->id); // 12345
    printf("%s\n", $firstImage->name); // alec snapshot
    printf("%s\n", $firstImage->distribution); // Ubuntu
    // ...
    $otherImage = $allImages->images[36];
    printf("%s\n", $otherImage->id); // 32399
    printf("%s\n", $otherImage->name); // Fedora 17 x32 Desktop
    printf("%s\n", $otherImage->distribution); // Fedora
    // Returns all your images.
    $myImages = $images->getMyImages();
    printf("%s\n", $myImages->status); // OK
    $firstImage = $myImages->images[0];
    printf("%s\n", $firstImage->id); // 12345
    printf("%s\n", $firstImage->name); // my_image 2013-02-01
    printf("%s\n", $firstImage->distribution); // Ubuntu
    // Returns all global images.
    $globalImages = $images->getGlobal();
    printf("%s\n", $globalImages->status); // OK
    $anImage = $globalImages->images[9];
    printf("%s\n", $anImage->id); // 12573
    printf("%s\n", $anImage->name); // Debian 6.0 x64
    printf("%s\n", $anImage->distribution); // Debian
    // Displays the attributes of an image.
    printf("%s\n", $images->show(12574)->image->distribution); // CentOS
    // Destroys an image. There is no way to restore a deleted image so be careful and ensure
    // your data is properly backed up.
    $destroyImage = $images->destroy(12345);
    printf("%s\n", $destroyImage->status); // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

### SSH Keys ###

```php
// ...
$sshKeys = $digitalOcean->sshKeys();
try {
    // Returns all the available public SSH keys in your account that can be added to a droplet.
    $allSshKeys = $sshKeys->getAll();
    printf("%s\n", $allSshKeys->status); // OK
    $firstSshKey = $allSshKeys->ssh_keys[0];
    printf("%s\n", $firstSshKey->id); // 10
    printf("%s\n", $firstSshKey->name); // office-imac
    $otherSshKey = $allSshKeys->ssh_keys[1];
    printf("%s\n", $otherSshKey->id); // 11
    printf("%s\n", $otherSshKey->name); // macbook-air
    // Shows a specific public SSH key in your account that can be added to a droplet.
    $sshKey = $sshKeys->show(10);
    printf("%s\n", $sshKey->status); // OK
    printf("%s\n", $sshKey->ssh_key->id); // 10
    printf("%s\n", $sshKey->ssh_kay->name); // ssh-dss AHJASDBVY6723bgB...I0Ow== me@office-imac
    // Adds a new public SSH key to your account. The argument should be an array with name and ssh_key_pub keys.
    $addSshKey = $sshKeys->add(array(
        'name'        => 'macbook_pro',
        'ssh_key_pub' => 'ssh-dss AHJASDBVY6723bgB...I0Ow== me@macbook_pro',
    ));
    printf("%s\n", $addSshKey->status); // OK
    printf("%s\n", $addSshKey->ssh_key->id); // 12
    printf("%s\n", $addSshKey->ssh_kay->name); // macbook_pro
    printf("%s\n", $addSshKey->ssh_kay->name); // ssh-dss AHJASDBVY6723bgB...I0Ow== me@macbook_pro
    // Edits an existing public SSH key in your account. The argument should be an array with ssh_key_pub key.
    $editSshKey = $sshKeys->edit(array(
        'ssh_key_pub' => '...',
    )); // not implemented yet.
    // Deletes the SSH key from your account.
    $destroySshKey = $sshKeys->destroy(12);
    printf("%s\n", $destroySshKey->status); // OK
} catch (Exception $e) {
    die($e->getMessage());
}
```

### Sizes ###

```php
// ...
$sizes = $digitalOcean->sizes();
try {
    // Returns all the available sizes that can be used to create a droplet.
    $allSizes = $sizes->getAll();
    printf("%s\n", $allSizes->status); // OK
    $size1 = $allSizes->sizes[0];
    printf("%s, %s\n", $size1->id, $size1->name); // 33, 512MB
    // ...
    $size6 = $allSizes->sizes[5];
    printf("%s, %s\n", $size1->id, $size1->name); // 38, 16GB
} catch (Exception $e) {
    die($e->getMessage());
}
```


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