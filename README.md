Symfony 3.x with Vagrant and Puppet
=================

Getting started
---------------

Locally :
```bash
 vagrant up
```

VM :
```bash
 vagrant ssh
 cd /var/www/sf3fresh/current
 
./phing.sh composer.install
./phing.sh symfony.install-assets-symlink
```

Play: https://sf3fresh.local/

Executing Unit tests
--------------------

The application embeds the [PHPUnit][5] testing framework on the development environment.

Follow the [Symfony documentation][6] to create your unit test or copy/paste one of the samples into your bundle at `tests/<YourName>/<YourBundleName>/`.

To run the unit tests:
```bash
./phing.sh phpunit.run
```

  [3]: https://github.com/symfony/symfony-standard/tree/3.1 "The Symfony Standard Edition 3.1 release"
  [4]: https://symfony.com/roadmap "Symfony roadmap"
  [5]: https://phpunit.de/manual/current/en/ "The PHPUnit stable release"
  [6]: https://symfony.com/doc/current/book/testing.html "Symfony documentation"
