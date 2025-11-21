<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\User;

final class UserModelTest extends TestCase {
    public static function usernameProvider() : array {
        return [
            ['codingwithrk', 'codingwithrk'],
            ['  codingwithrk   ', 'codingwithrk'],
        ];
    }

    public function testUserId() : void {
        $user = new User();
        $userId = 1;
        $user->setId($userId);

        $this->assertSame($userId, $user->getId());
    }

    public function testUserFirstName() : void {
        $user = new User();
        $fistName = "Pappala";
        $user->setFirstName($fistName);
        
        $this->assertSame($fistName, $user->getFirstName());
       
    }

    public function testUserLastName() : void {
        $user = new User();
        $lastName = "Raj Kumar";
        $user->setLastName($lastName);
       
        $this->assertSame($lastName, $user->getLastName());
    }
    
    public function testUserUsername() : void {
        $user = new User();
        $userName = "codingwithrk";
        $user->setUsername($userName);
        
        $this->assertSame($userName, $user->getUsername());
    }

    #[DataProvider('usernameProvider')]
    public function testAddtionalUserUsername(string $username, string $expected) : void {
        $user = new User();
        $user->setUsername($username);

        $this->assertSame($expected, $user->getUsername());
    }

    public function testUserPassword() : void {
        $user = new User();
        $password = "codingwithrk";        
        $user->setPassword($password);

        $this->assertSame($password, $user->getPassword());
    }

    public function testUserEmail() : void {
        $user = new User();
        $email = "connect@codingwithrk.com";
        $user->setEmail($email);

        $this->assertSame($email, $user->getEmail());
    }

    public function testEmailException() {
        $user = new User();
        $email = "connectcodingwithrkcom";

        $this->expectException(Exception::class);
        $user->setEmail($email);
    }
}