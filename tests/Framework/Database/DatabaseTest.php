<?php

namespace Tests\Framework\Database;

use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseTest extends TestCase
{
    private $manager;

    protected $pdo;

    protected function getPDO()
    {
        $pdo = new PDO("sqlite::memory:");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $pdo;
    }

    protected function migrateDatabase($pdo)
    {
        $configArray = require 'phinx.php';

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);

        $configArray['paths']['migrations'][] = __DIR__ . '/../../Resources/db/migrations';
        $configArray['paths']['seeds'][] = __DIR__ . '/../../Resources/db/seeds';
        $configArray['environments']['framework.local.test'] = [
            'adapter' => 'sqlite',
            'connection' => $pdo
        ];

        $config = new Config($configArray);
        $this->manager = new Manager($config, new StringInput(''), new NullOutput());
        $this->manager->migrate('framework.local.test');

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    protected function seedDatabase($pdo)
    {
        $this->migrateDatabase($pdo);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->manager->seed('framework.local.test');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}
