<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \App\Models\MysqlAdapter;
use App\Controllers\UserController;
use Monolog\Logger;
use App\Models\User;

final class UserControllerDbTest extends TestCase
{
    protected $userController;
    protected $user;

    protected function setUp(): void
    {
        shell_exec('mysql -u root -e "CREATE DATABASE IF NOT EXISTS phpunittest"');
        $config = require dirname(__DIR__) . '/Fixtures/settings.php';
        $db_conf = dirname(__DIR__) . '/Fixtures/config.cnf';
        $cmd = 'mysql --defaults-extra-file=' . $db_conf . '<' . dirname(__DIR__) . '/Fixtures/db.sql';
        shell_exec($cmd);

        $this->user = new User();
        $this->user->setFirstName('Pappala');
        $this->user->setLastName('Raj Kumar');
        $this->user->setUsername('codingwithrk');
        $this->user->setPassword('password@123');
        $this->user->setEmail('connect@codingwithrk.com');

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $mysql_adapter = new MysqlAdapter($config['db_dns'], $config['db_user'], $config['db_password']);
        $this->userController = new UserController($mysql_adapter, $logger);
    }

    protected function tearDown(): void
    {
        $this->userController = null;
        $this->user = null;
    }

    public function testUserControllerDbUserFetchAll(): void
    {
        $users = $this->userController->fetchAll();

        self::assertIsArray($users);
        self::assertCount(3, $users);

        $user = $users[0];
        self::assertEquals(1, $user['id']);
        self::assertEquals('Bob', $user['first_name']);
        self::assertEquals('Smith', $user['last_name']);
        self::assertEquals('bsmith', $user['username']);
        self::assertEquals('bsmith@example.com', $user['email']);

        $user = $users[1];
        self::assertEquals(2, $user['id']);
        self::assertEquals('Steven', $user['first_name']);
        self::assertEquals('Robertson', $user['last_name']);
        self::assertEquals('srobertson', $user['username']);
        self::assertEquals('srobertson@example.com', $user['email']);

        $user = $users[2];
        self::assertEquals(3, $user['id']);
        self::assertEquals('Rebecca', $user['first_name']);
        self::assertEquals('Johnes', $user['last_name']);
        self::assertEquals('rjones', $user['username']);
        self::assertEquals('rjones@example.com', $user['email']);

        self::assertJson(json_encode($users));
    }

    public function testUserControllerDbUserFetchById(): void
    {
        $user = $this->userController->fetchById(1);

        self::assertEquals(1, $user['id']);
        self::assertEquals('Bob', $user['first_name']);
        self::assertEquals('Smith', $user['last_name']);
        self::assertEquals('bsmith', $user['username']);
        self::assertEquals('bsmith@example.com', $user['email']);
    }

    public function testUserControllerDbUserInsert(): void
    {
        $user = $this->userController->insert($this->user);

        self::assertEquals(4, $user['id']);
        self::assertEquals($this->user->getFirstName(), $user['first_name']);
        self::assertEquals($this->user->getLastName(), $user['last_name']);
        self::assertEquals($this->user->getUsername(), $user['username']);
        self::assertEquals($this->user->getEmail(), $user['email']);

        self::assertJson(json_encode($user));
    }

    public function testUserControllerDbUserUpdate(): void
    {
        $email = 'test@example.com';
        $user = $this->userController->insert($this->user);

        $this->user->setId($user['id']);
        $this->user->setEmail($email);

        $user = $this->userController->update($this->user);

        self::assertEquals($email, $user['email']);

        self::assertJson(json_encode($user));
    }

    public function testUserControllerDbUserDelete() : void {
        $user = $this->userController->insert($this->user);

        $this->user->setId($user['id']);
        
        $users = $this->userController->fetchAll();

        self::assertCount(4, $users);

        $this->userController->delete($this->user->getId());

        $users = $this->userController->fetchAll();

        self::assertCount(3, $users);
    }
}