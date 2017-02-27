Fresh Symfony 3.x
=================
[![Build Status](https://travis-ci.org/kmelia/fresh-symfony.svg?branch=master)](https://travis-ci.org/kmelia/fresh-symfony/branches)

Provides a [**Fresh Symfony**][1] to start your application quickly.

The application is based on the **latest stable 3.x branch**, according to the [Symfony Roadmap][4].

You can also find *other available releases* of the application:

 * the part [2.7 LTS][9],
 * the part [2.3 LTS][8].

Getting started
---------------
**1.** Download the [**latest stable release** [tar.gz]][2] inherited from [Symfony 3.1][3].

**2.** Then, run the following commands:
```bash
./phing.sh composer.install
./phing.sh symfony.install-assets-symlink
```

**3.** Finally, [configure your project][7].

That's it!

Executing Unit tests
--------------------

The application embeds the [PHPUnit][5] testing framework on the development environment.

Follow the [Symfony documentation][6] to create your unit test or copy/paste one of the samples into your bundle at `tests/<YourName>/<YourBundleName>/`.

To run the unit tests:
```bash
./phing.sh phpunit.run
```


  [1]: https://bitbucket.org/kmelia/fresh-symfony "Fresh Symfony"
  [2]: https://bitbucket.org/kmelia/fresh-symfony/get/master.tar.gz "Latest stable release of Fresh Symfony"
  [3]: https://github.com/symfony/symfony-standard/tree/3.1 "The Symfony Standard Edition 3.1 release"
  [4]: https://symfony.com/roadmap "Symfony roadmap"
  [5]: https://phpunit.de/manual/current/en/ "The PHPUnit stable release"
  [6]: https://symfony.com/doc/current/book/testing.html "Symfony documentation"
  [7]: https://bitbucket.org/kmelia/fresh-symfony/src/master/CONFIGURE.md "Fresh Symfony documentation"
  [8]: https://bitbucket.org/kmelia/fresh-symfony/src/release/2.3_LTS/README.md "Fresh Symfony 2.3 LTS"
  [9]: https://bitbucket.org/kmelia/fresh-symfony/src/release/2.7_LTS/README.md "Fresh Symfony 2.7 LTS"
