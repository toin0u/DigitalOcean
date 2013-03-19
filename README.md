DigitalOcean
============

This PHP 5.3+ library helps you to interact with the [DigitalOcean](https://www.digitalocean.com/)
[API](https://www.digitalocean.com/api) via PHP or [CLI](#cli).

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

* `CurlHttpAdapter` to use [cURL](http://php.net/manual/book.curl.php) - it's the default one.
* `BuzzHttpAdapter` to use [Buzz](https://github.com/kriswallsmith/Buzz), a lightweight PHP 5.3 library for
issuing HTTP requests.
* `GuzzleHttpAdapter` to use [Guzzle](https://github.com/guzzle/guzzle), PHP 5.3+ HTTP client and framework
for building RESTful web service clients.
* `ZendHttpAdapter` to use [Zend Http Client](http://framework.zend.com/manual/2.0/en/modules/zend.http.client.html).
* `SocketHttpAdapter` to use a [socket](http://www.php.net/manual/function.fsockopen.php).

```php
require 'vendor/autoload.php';

use DigitalOcean\DigitalOcean;
use DigitalOcean\Credentials;

// Set up your credentials.
$credentials = new Credentials('YOUR_CLIENT_ID', 'YOUR_API_KEY')
// Use the default adapter, CurlHttpAdapter.
$digitalOcean = new DigitalOcean($credentials);
// Or use BuzzHttpAdatpter.
$digitalOcean = new DigitalOcean($credentials, new DigitalOcean\HttpAdapter\BuzzHttpAdapter());
// ...
```


API
---

### Droplets ###

```php
// ...
$droplets = $digitalOcean->droplets(); // alias to Droplets class.
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

    // Creates a new droplet. The argument should be an array with 4 required keys:
    // name, sized_id, image_id and region_id. ssh_key_ids key is optional but if any it should be a string.
    $createDroplet = $droplets->create(array(
        'name'        => 'my_new_droplet',
        'size_id'     => 123,
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
    $resizeDroplet = $droplets->resize(12345, array('size_id' => 123));
    printf("%s, %s\n", $resizeDroplet->status, $resizeDroplet->event_id);

    // Takes a snapshot of the running droplet, which can later be restored or used to create a new droplet
    // from the same image. The argument can be an empty array or an array with name key.
    $snapshotDroplet = $droplets->snapshot(12345, array('name' => 'my_snapshot'));
    printf("%s, %s\n", $snapshotDroplet->status, $snapshotDroplet->event_id);

    // Restores a droplet with a previous image or snapshot. The argument should be an array with image_id key.
    $restoreDroplet = $droplets->restore(12345, array('image_id' => 123));
    printf("%s, %s\n", $restoreDroplet->status, $restoreDroplet->event_id);

    // Reinstalls a droplet with a default image. The argument should be an array with image_id key.
    $rebuildDroplet = $droplets->rebuild(12345, array('image_id' => 123));
    printf("%s, %s\n", $rebuildDroplet->status, $rebuildDroplet->event_id);

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
$regions = $digitalOcean->regions(); // alias to Regions class.
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
$images = $digitalOcean->images(); // alias to Images class.
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
$sshKeys = $digitalOcean->sshKeys(); // alias to SSHKeys class.
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
    printf("%s\n", $sshKey->ssh_kay->name); // office-imac
    printf("%s\n", $sshKey->ssh_kay->ssh_pub_key); // ssh-dss AHJASDBVY6723bgB...I0Ow== me@office-imac

    // Adds a new public SSH key to your account. The argument should be an array with name and ssh_key_pub keys.
    $addSshKey = $sshKeys->add(array(
        'name'        => 'macbook_pro',
        'ssh_key_pub' => 'ssh-dss AHJASDBVY6723bgB...I0Ow== me@macbook_pro',
    ));
    printf("%s\n", $addSshKey->status); // OK
    printf("%s\n", $addSshKey->ssh_key->id); // 12
    printf("%s\n", $addSshKey->ssh_kay->name); // macbook_pro
    printf("%s\n", $addSshKey->ssh_kay->ssh_pub_key); // ssh-dss AHJASDBVY6723bgB...I0Ow== me@macbook_pro

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
$sizes = $digitalOcean->sizes(); // alias to Sizes class.
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

### CLI ###

To use the Command-Line Interface, you need to rename the `credentials.yml.dist` file to `credentials.yml`, then
add your own Client ID and API key:

```yml
CLIENT_ID:  <YOUR_CLIENT_ID>
API_KEY:    <YOUR_API_KEY>
```

If you want to use another credential file just add the option `--credentials="/path/to/file"` to commands below.

Commands for `Droplets`:

```bash
$ php digitalocean list droplets

$ php digitalocean droplets:show-all-active
// 1 | id:12345 | name:my_drop | image_id:54321 | size_id:66 | region_id:2 | backups_active:1 | ip_address:127.0.0.1 | status:active
// ...

$ php digitalocean droplets:show 12345
// id:             12345
// name:           my_drop
// image_id:       54321
// size_id:        66
// region_id:      2
// backups_active: 1
// ip_address:     127.0.0.1
// status:         active

$ php digitalocean droplets:create my_new_droplet 66 1601 2
// Creates a new droplet named "my_new_droplet" with 512MB and CentOS 5.8 x64 in Amsterdam without any SSH keys.

$ php digitalocean droplets:create test_droplet 65 43462 1 "5555,5556"
// Creates a new droplet named "test_droplet" with 8BG and Ubuntu 11.04x32 Desktop in New York with 2 SSH keys.
// status:   OK
// event_id: 6895

$ php digitalocean droplets:reboot 12345
// status:   OK
// event_id: 6895

$ php digitalocean droplets:power-cycle 12345
// status:   OK
// event_id: 6895

$ php digitalocean droplets:shutdown 12345
// status:   OK
// event_id: 6895

$ php digitalocean droplets:power-on 12345
// status:   OK
// event_id: 6892

$ php digitalocean droplets:power-off 12345
// status:   OK
// event_id: 6893

$ php digitalocean droplets:reset-root-password 12345
// status:   OK
// event_id: 6894

$ php digitalocean droplets:resize 12345 62 // resizes to 2GB
// status:   OK
// event_id: 6895

$ php digitalocean droplets:snapshot 12345 my_new_snapshot // the name is optional
// status:   OK
// event_id: 6896

$ php digitalocean droplets:restore 12345 46964 // restores to "LAMP on Ubuntu 12.04" image
// status:   OK
// event_id: 6897

$ php digitalocean droplets:rebuild 12345 1601 // rebuilds to "CentOS 5.8 x64" image
// status:   OK
// event_id: 6898

$ php digitalocean droplets:enable-automatic-backups 12345
// status:   OK
// event_id: 6899

$ php digitalocean droplets:disable-automatic-backups 12345
// status:   OK
// event_id: 6901

$ php digitalocean droplets:destroy 12345
// id:       12345
// event_id: 6902
```

Commands for `Images`:

```bash
$ php digitalocean list images

$ php digitalocean images:all
// 1 | id:13245 | name:my_image 2013-02-17 | distribution:Ubuntu
// 2 | id:21345 | name:my_image 2013-02-24 | distribution:Ubuntu
// ...
// 45 | id:46964 | name:LAMP on Ubuntu 12.04 | distribution:Ubuntu

$ php digitalocean images:mines
// 1 | id:13245 | name:my_image 2013-02-17 | distribution:Ubuntu
// ...

$ php digitalocean images:global
// 1 | id:1601 | name:CentOS 5.8 x64 | distribution:CentOS
// 2 | id:1602 | name:CentOS 5.8 x32 | distribution:CentOS
// ...
// 27 | id:43462 | name:Ubuntu 11.04x32 Desktop | distribution:Ubuntu
// 28 | id:46964 | name:LAMP on Ubuntu 12.04 | distribution:Ubuntu

$ php digitalocean images:show 46964
// id:           46964
// name:         LAMP on Ubuntu 12.04
// distribution: Ubuntu

$ php digitalocean images:destroy 12345
// id:       12345
// event_id: 7901
```

Commands for `Regions`:

```bash
$ php digitalocean list regions

$ php digitalocean regions:all
// 1 | id:1 | name:New York 1
// 2 | id:2 | name:Amsterdam 1
```

Commands for `Sizes`:

```bash
$ php digitalocean list sizes

$ php digitalocean sizes:all
// 1 | id:66 | name:512MB
// 2 | id:63 | name:1GB
// 3 | id:62 | name:2GB
// 4 | id:64 | name:4GB
// 5 | id:65 | name:8GB
// 6 | id:61 | name:16GB
// 7 | id:60 | name:32GB
// 8 | id:70 | name:48GB
// 9 | id:69 | name:64GB
// 10 | id:68 | name:96GB
```

Commands for `SSH Keys`:

```bash
$ php digitalocean list ssh-keys

$ php digitalocean ssh-keys:all
// 1 | id:5555 | name:my_pub_ssh_key

$ php digitalocean ssh-keys:show 5555
// id:   5555
// name: my_pub_ssh_key
// key:  ssh-dss AAAAB3NzaC1.......UcviwfZspUcoDbnwk= dev@my_pub_ssh_key

$ php digitalocean ssh-keys:add my_new_ssh_key "ssh-dss DFDSFSDFC1.......FDSFewf987fdsf= dev@my_new_ssh_key"
// id:   5556
// name: my_new_ssh_key
// key:  ssh-dss DFDSFSDFC1.......FDSFewf987fdsf= dev@my_new_ssh_key

$ php digitalocean ssh-keys:destroy 5556
// status:   OK
// event_id: 7902
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

Rename the `phpunit.xml.dist` file to `phpunit.xml`, then uncomment the following lines and add your own Client ID and
API key:

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


Acknowledgments
---------------
* [Symfony Console Component](https://packagist.org/packages/symfony/console)
* [Symfony Yaml Component](https://packagist.org/packages/symfony/yaml)


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