[![Latest Stable Version](https://poser.pugx.org/thecodingmachine/doctrine-migrations-universal-module/v/stable)](https://packagist.org/packages/thecodingmachine/doctrine-migrations-universal-module)
[![Latest Unstable Version](https://poser.pugx.org/thecodingmachine/doctrine-migrations-universal-module/v/unstable)](https://packagist.org/packages/thecodingmachine/doctrine-migrations-universal-module)
[![License](https://poser.pugx.org/thecodingmachine/doctrine-migrations-universal-module/license)](https://packagist.org/packages/thecodingmachine/doctrine-migrations-universal-module)
[![Build Status](https://travis-ci.org/thecodingmachine/doctrine-migrations-universal-module.svg?branch=master)](https://travis-ci.org/thecodingmachine/doctrine-migrations-universal-module)

# Doctrine Migrations universal module

This package integrates Doctrine Migrations in any [container-interop](https://github.com/container-interop/service-provider) compatible framework/container.

## Installation

```
composer require thecodingmachine/doctrine-migrations-universal-module
```

Once installed, you need to register the [`TheCodingMachine\DoctrineMigrationsServiceProvider`](src/DoctrineMigrationsServiceProvider.php) into your container.

If your container supports [thecodingmachine/discovery](https://github.com/thecodingmachine/discovery) integration, you have nothing to do. Otherwise, refer to your framework or container's documentation to learn how to register *service providers*.

## Introduction

This service provider is meant to add support for Doctrine migrations in your application.
It is expected that your application already has a Doctrine DBAL connection and a Symfony Console.

Both packages are dependencies of this package.

## Expected values / services

This *service provider* expects the following configuration / services to be available:

| Name                             | Compulsory | Description                            |
|----------------------------------|------------|----------------------------------------|
| `doctrine_migrations.directory`  | *no*       | The directory containing Doctrine migrations. By default, the service provider will guess it from your composer.json file. |
| `doctrine_migrations.namespace`  | *no*       | The namespace containing Doctrine migrations. By default, the service provider will guess it from your composer.json file. |
| `doctrine_migrations.table_name` | *no*       | The name of the "versions" table created by Doctrine Migrations in your schema to keep track of applied migrations. |

## Provided services

This *service provider* provides the following services:

| Service name                | Description                          |
|-----------------------------|--------------------------------------|
| `Doctrine\DBAL\Migrations\Configuration\Configuration`             | An instance containing the configuration of Doctrine Migrations                           |

## Extended services

This *service provider* extends those services:

| Name                                    | Compulsory | Description                            |
|-----------------------------------------|------------|----------------------------------------|
| `Symfony\Component\Console\Application` | *yes*      | The Symfony console                    |


<small>Project template courtesy of <a href="https://github.com/thecodingmachine/service-provider-template">thecodingmachine/service-provider-template</a></small>
