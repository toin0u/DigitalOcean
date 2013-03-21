CHANGELOG
=========

1.1.0 (2013-03-21)
------------------

* Added: distribution credential file as a constant
* Added: edit command to CLI - fix #13
* Added: ask confirmation on droplet reboot command - fix #21
* Added: ask confirmation on droplet rebuild command - fix #20
* Added: ask confirmation on droplet reset root password command - fix #19
* Added: ask confirmation on droplet resize command - fix #18
* Added: ask confirmation on droplet restore command - fix #17
* Added: ask confirmation on droplet shutdown command - fix #16
* Added: ask confirmation on ssh key destroy command - fix #14
* Added: ask confirmation on image destroy command - fix #14
* Added: ask confirmation on droplet destroy command - fix #14
* Fixed: doc about credential file and removed screenshot

1.0.0 (2013-03-19)
------------------

* Added: tests to ssh keys and command CLI
* Added: tests to images CLI
* Added: tests to droplets CLI
* Added: tests to regions and sizes CLI
* Updated: doc with credentials option
* Updated: doc about CLI
* Added: CLI

0.2.0 (2013-03-18)
------------------

* Fixed: tests
* Added: Credential class + test [BC break]
* Added: cURL adapter is the default one
* Updated: doc with exemples - fix #11
* Added: check when adding ssh keys to new droplets

0.1.1 (2013-03-15)
------------------

* Fixed: class names more consistant [BC break]
* Fixed: adapter test filenames
* Fixed: credits
* Updated: composer.json and doc
* Added: SocketAdapter + test - fix #9
* Added: ZendAdapter + test - fix #10
* Added: GuzzleAdapter + test - fix #7
* Added: BuzzAdapter + test - fix #8

0.1.0 (2013-03-15)
------------------

* Updated: doc with exemples
* Refactored: api url construction
* Refactored: tests
* Updated: composer.json keywords
* Added: SSH Keys API + test - fix #3
* Fixed: Size alias
* Added: Sizes API + test - fix #4
* Added: Images API + test - fix #2
* Updated: regions() method to all()
* Refactored: use ReflectionMethod() in tests
* Added: Regions API + test - fix #1
* Refactored: constructor, buildQuery and processQuery
* Refactored: droplet actions
* Fixed: composer.json name convention
* Added: travis-ci and stillmaintained
* Initial import
