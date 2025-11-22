<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\User;

final class UserModelTest extends TestCase {
    protected $user;

    public function setUp() : void {
        $this->user = new User();
    }

    public function tearDown() : void {
        $this->user = null;
    }
    
    public static function usernameProvider() : array {
        return [
            ['codingwithrk', 'codingwithrk'],
            ['  codingwithrk   ', 'codingwithrk'],
        ];
    }

    public function testUserId() : void {
        $userId = 1;
        $this->user->setId($userId);

        $this->assertSame($userId, $this->user->getId());
    }

    public function testUserFirstName() : void {
        $fistName = "Pappala";
        $this->user->setFirstName($fistName);
        
        $this->assertSame($fistName, $this->user->getFirstName());
       
    }

    public function testUserLastName() : void {
        $lastName = "Raj Kumar";
        $this->user->setLastName($lastName);
       
        $this->assertSame($lastName, $this->user->getLastName());
    }
    
    public function testUserUsername() : void {
        $userName = "codingwithrk";
        $this->user->setUsername($userName);
        
        $this->assertSame($userName, $this->user->getUsername());
    }

    #[DataProvider('usernameProvider')]
    public function testAddtionalUserUsername(string $username, string $expected) : void {
        $this->user->setUsername($username);

        $this->assertSame($expected, $this->user->getUsername());
    }

    public function testUserPassword() : void {
        $password = "codingwithrk";        
        $this->user->setPassword($password);

        $this->assertSame($password, $this->user->getPassword());
    }

    public function testUserEmail() : void {
        $email = "connect@codingwithrk.com";
        $this->user->setEmail($email);

        $this->assertSame($email, $this->user->getEmail());
    }

    public function testEmailException() {
        $email = "connectcodingwithrkcom";

        $this->expectException(Exception::class);
        $this->user->setEmail($email);
    }
}