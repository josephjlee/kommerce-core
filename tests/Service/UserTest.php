<?php
namespace inklabs\kommerce\Service;

use inklabs\kommerce\Entity;
use inklabs\kommerce\View;
use inklabs\kommerce\Lib;
use inklabs\kommerce\tests\Helper;
use inklabs\kommerce\tests\EntityRepository\FakeUser;

class UserTest extends Helper\DoctrineTestCase
{
    /** @var FakeUser */
    protected $userRepository;

    /** @var User */
    protected $userService;

    /** @var Lib\ArraySessionManager */
    protected $sessionManager;

    public function setUp()
    {
        $this->userRepository = new FakeUser;

        $this->sessionManager = new Lib\ArraySessionManager;
        $this->userService = new User($this->userRepository, $this->sessionManager);
    }

    public function testFind()
    {
        $viewUser = $this->userService->find(1);
        $this->assertTrue($viewUser instanceof View\User);
    }

    public function testFindNotFound()
    {
        $this->userRepository->setReturnValue(null);

        $viewUser = $this->userService->find(0);
        $this->assertSame(null, $viewUser);
    }

    public function testUserLogin()
    {
        $user = $this->getDummyUser();
        $this->userRepository->setReturnValue($user);

        $this->assertSame(0, $user->getTotalLogins());

        $loginResult = $this->userService->login('test@example.com', 'xxxx', '127.0.0.1');

        $this->assertSame(1, $user->getTotalLogins());
        $this->assertTrue($loginResult);
    }

    public function testUserLoginWithWrongPassword()
    {
        $user = $this->getDummyUser();
        $this->userRepository->setReturnValue($user);

        $this->assertSame(0, $user->getTotalLogins());

        $loginResult = $this->userService->login('test@example.com', 'zzz', '127.0.0.1');

        $this->assertSame(0, $user->getTotalLogins());
        $this->assertFalse($loginResult);
    }

    public function testUserLoginWithWrongEmail()
    {
        $this->userRepository->setReturnValue(null);

        $loginResult = $this->userService->login('zzz@example.com', 'xxxx', '127.0.0.1');

        $this->assertFalse($loginResult);
    }

    public function testLogout()
    {
        $user = $this->getDummyUser();
        $this->userRepository->setReturnValue($user);

        $loginResult = $this->userService->login('test@example.com', 'xxxx', '127.0.0.1');

        $this->assertTrue($loginResult);
        $this->assertTrue($this->userService->getUser() instanceof Entity\User);

        $this->userService->logout();

        $this->assertSame(null, $this->userService->getUser());
    }

    public function testGetAllUsers()
    {
        $users = $this->userService->getAllUsers();
        $this->assertTrue($users[0] instanceof View\User);
    }

    public function testAllGetUsersByIds()
    {
        $users = $this->userService->getAllUsersByIds([1]);
        $this->assertTrue($users[0] instanceof View\User);
    }
}
