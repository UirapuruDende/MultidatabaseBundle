# DendeMultidatabaseBundle

A [Symfony 2](http://symfony.com) bundle for providing an easy to use database switching infrastructure

[![Build Status](https://travis-ci.org/UirapuruDende/CalendarBundle.svg?branch=master)](https://travis-ci.org/UirapuruDende/CalendarBundle)

## installation:

1. install via composer

    composer require dende/multidatabase-bundle

2. enable bundle in AppKernel

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Dende\MultidatabaseBundle\DendeMultidatabaseBundle(),
            ...
        );
    }
}
```
