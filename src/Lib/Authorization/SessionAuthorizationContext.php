<?php
namespace inklabs\kommerce\Lib\Authorization;

use inklabs\kommerce\Lib\Uuid;
use inklabs\kommerce\Lib\UuidInterface;

class SessionAuthorizationContext implements AuthorizationContextInterface
{
    /** @var UuidInterface */
    private $userId;

    /** @var string */
    private $sessionId;

    /** @var bool */
    private $isAdmin;

    /**
     * @param UuidInterface $userId
     * @param bool $isAdmin
     */
    public function __construct(UuidInterface $userId = null, $isAdmin = true)
    {
        if ($userId === null) {
            // TODO: Remove and pass correct user
            $userId = Uuid::uuid4();
        }

        $this->userId = $userId;
        $this->isAdmin = $isAdmin;
    }

    public function verifyCanMakeRequests()
    {
        return;
    }

    public function verifyCanManageCart(UuidInterface $cartId)
    {
        // TODO: Validate user can access cart via userId or sessionId
        if (! $this->isAdmin()) {
            throw AuthorizationContextException::cartAccessDenied();
        }
    }

    public function verifyCanManageUser(UuidInterface $userId)
    {
        if (! $this->userId->equals($userId)) {
            if (! $this->isAdmin()) {
                throw AuthorizationContextException::userAccessDenied();
            }
        }
    }

    public function verifyIsAdmin()
    {
        if (! $this->isAdmin()) {
            throw AuthorizationContextException::accessDenied();
        }

    }

    public function verifyCanViewOrder(UuidInterface $orderId)
    {
        // TODO: Validate user can access order
        if (! $this->isAdmin()) {
            throw AuthorizationContextException::accessDenied();
        }
    }

    private function isAdmin()
    {
        return $this->isAdmin;
    }
}
