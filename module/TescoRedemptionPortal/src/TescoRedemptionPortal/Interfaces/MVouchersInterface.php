<?php

namespace TescoRedemptionPortal\Interfaces;

interface MVouchersInterface
{
    function create();
    function verify($token);
    function redeem($token);
}