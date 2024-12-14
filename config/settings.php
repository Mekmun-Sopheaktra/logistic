<?php

use App\Constants\ConstUserRole;

return [
    'default_role' => env('ROLE_DEFAULT', ConstUserRole::VENDOR),
    'account_status' => env('ACCOUNT_STATUS', true),
    'roles' => [
        'employee' => ConstUserRole::EMPLOYEE,
        'admin' => ConstUserRole::ADMIN,
        'vendor' => ConstUserRole::VENDOR,
    ]
];
