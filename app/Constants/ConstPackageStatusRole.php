<?php

namespace App\Constants;

use ReflectionClass;

class ConstPackageStatusRole
{
    const COMPLETED = 1;
    const HOLD = 2;
    const CANCELLED = 3;

    /**
     * Get all constants
     */
    public static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
