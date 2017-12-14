<?php

namespace TheCodingMachine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Mouf\Composer\ClassNameMapper;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use TheCodingMachine\Funky\Annotations\Extension;
use TheCodingMachine\Funky\Annotations\Factory;
use TheCodingMachine\Funky\ServiceProvider;

class DoctrineMigrationsServiceProvider extends ServiceProvider
{
    /**
     * @Extension()
     */
    public static function extendConsole(Application $console, Configuration $configuration): Application
    {
        $commands = [
            new ExecuteCommand(),
            new GenerateCommand(),
            new LatestCommand(),
            new MigrateCommand(),
            new StatusCommand(),
            new VersionCommand(),
            new UpToDateCommand(),
        ];

        foreach ($commands as $command) {
            $command->setMigrationConfiguration($configuration);
        }

        $console->addCommands($commands);

        if ($console->getHelperSet()->has('em')) {
            $console->add(new DiffCommand());
        }

        return $console;
    }

    private static $classNameMapper;

    private static function getClassNameMapper(): ClassNameMapper
    {
        if (self::$classNameMapper === null) {
            self::$classNameMapper = ClassNameMapper::createFromComposerFile();
        }
        return self::$classNameMapper;
    }

    /**
     * @Factory(name="doctrine_migrations.namespace")
     * @return string
     */
    public static function getNamespace(): string
    {
        $mapper = self::getClassNameMapper();
        $namespaces = $mapper->getManagedNamespaces();
        if (empty($namespaces)) {
            throw new DoctrineMigrationsServiceProviderException('You composer.json file does not declare any PSR-0 or PSR-4 namespace.');
        }

        $namespace = $namespaces[0];
        return trim($namespace, '\\').'\\Migrations';
    }

    /**
     * @Factory(name="doctrine_migrations.directory")
     * @return string
     */
    public static function getDirectory(): string
    {
        $mapper = self::getClassNameMapper();
        $testClass = self::getNamespace().'\\FooBar';

        $files = $mapper->getPossibleFileNames($testClass);
        return substr($files[0], 0, -11);
    }

    /**
     * @Factory()
     */
    public static function getConfiguration(ContainerInterface $container, Connection $connection): Configuration
    {
        $configuration = new Configuration($connection);
        if ($container->has('doctrine_migrations.table_name')) {
            $configuration->setMigrationsTableName($container->get('doctrine_migrations.table_name'));
        }
        $configuration->setMigrationsDirectory($container->get('doctrine_migrations.directory'));
        $configuration->setMigrationsNamespace($container->get('doctrine_migrations.namespace'));

        // TODO: allow registering any number of directories (for packages that provide their own migrations)
        //$configuration->registerMigrationsFromDirectory(...);

        return $configuration;
    }
}
