<?php

namespace TheCodingMachine;

use PHPUnit\Framework\TestCase;
use Simplex\Container;
use Symfony\Component\Console\Application;

class DoctrineMigrationsServiceProviderTest extends TestCase
{
    public function testCli()
    {
        $container = new Container([
            new DoctrineMigrationsServiceProvider(),
            new DbalServiceProvider(),
            new SymfonyConsoleServiceProvider(),
        ]);

        global $db_host, $db_username, $db_password, $db_name;

        $container->set('dbal.dbname', $db_name);
        $container->set('dbal.host', $db_host);
        $container->set('dbal.user', $db_username);
        $container->set('dbal.password', $db_password);

        $console = $container->get(Application::class);
        /* @var $console \Symfony\Component\Console\Application */
        $this->assertTrue($console->has('migrations:execute'));
    }

    public function testGetNamespace()
    {
        $this->assertSame('TheCodingMachine\\Migrations', DoctrineMigrationsServiceProvider::getNamespace());
    }

    public function testGetDirectory()
    {
        $this->assertSame('src/Migrations', DoctrineMigrationsServiceProvider::getDirectory());
    }
}
