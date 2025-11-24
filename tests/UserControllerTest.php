<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \App\Models\MysqlAdapter;
use App\Controllers\UserController;
use App\Models\Emailer;
use App\Models\User;
use Monolog\Logger;

final class UserControllerTest extends TestCase
{
    protected MysqlAdapter $mysql_adapter;
    protected Emailer $emailer;
    protected $register_user_data;

    protected function setUp(): void
    {
        $this->register_user_data = [
            'first_name' => 'Mark',
            'last_name' => 'Antony',
            'username' => 'mantony',
            'password' => 'test@1234!',
            'email' => 'test@mark.com',
        ];

        $this->mysql_adapter = $this->getMockBuilder(MysqlAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->emailer = $this->getMockBuilder(Emailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->emailer->method('send')
            ->willReturn(true);
    }

    public function testUserRegisterAccount() : void {
        $user = new User();
        $user->setFirstName($this->register_user_data['first_name']);
        $user->setLastName($this->register_user_data['last_name']);
        $user->setUsername($this->register_user_data['username']);
        $user->setPassword($this->register_user_data['password']);
        $user->setEmail($this->register_user_data['email']);

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $this->mysql_adapter->method('insert')
            ->willReturn(7);

        $userController = new UserController($this->mysql_adapter, $logger, $this->emailer);
        $new_user = $userController->registerUser($user);

        self::assertEquals(7, $new_user['id']);
    }
}