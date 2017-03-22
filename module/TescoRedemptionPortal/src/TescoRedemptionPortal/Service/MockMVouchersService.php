<?php

namespace TescoRedemptionPortal\Service;

use TescoRedemptionPortal\Interfaces\MVouchersInterface;
use TescoRedemptionPortal\Exception\TokenCancelledException;
use TescoRedemptionPortal\Exception\TokenExpiredException;
use TescoRedemptionPortal\Exception\TokenNotRecognisedException;
use TescoRedemptionPortal\Exception\TokenSuspendedException;
use TescoRedemptionPortal\Exception\VoucherLockedException;

class MockMVouchersService implements MVouchersInterface
{
    protected $tokens = array(
        'suspended' => array(
            'status' => 'SUSPENDED'
        ),
        'cancelled' => array(
            'status' => 'CANCELLED'
        ),
        'expired' => array(
            'status' => 'EXPIRED'
        ),
        'locked' => array(
            'status' => 'LOCKED'
        )
    );

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function verify($token)
    {
        return $this -> getMockToken($token);
    }

    public function redeem($token)
    {
        // TODO: Implement redeem() method.
    }

    protected function getMockToken($token)
    {
        if (is_numeric ($token)) {
            return  array(
                'status' => 'ACTIVE',
                'value' => $token * 100
            );
        } else if (isset($this->tokens[$token])) {
            $foundToken = $this->tokens[$token];

            switch ($foundToken['status']) {
                case 'SUSPENDED':
                    throw new TokenSuspendedException();
                case 'CANCELLED':
                    throw new TokenCancelledException();
                case 'EXPIRED':
                    throw new TokenExpiredException();
                case 'LOCKED':
                    throw new VoucherLockedException();
            }

            /*$model = new Token();
            $model->setToken($foundToken['token']);
            $model->setPin($foundToken['pin']);
            $model->setTokenStatus($foundToken['status']);
            $model->setBalance($foundToken['balance']);
            $model->setProgrammeId($foundToken['resourceId']);*/

            return $foundToken;
        } else {
            throw new TokenNotRecognisedException();
        }
    }
}