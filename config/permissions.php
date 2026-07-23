<?php
// config/permissions.php - Auto-updated

return [
    'roles' => [
        'admin' => 1,
        'manager' => 2,
        'cashier' => 3,
    ],

    'permissions' => [
        'view_dashboard' => ['admin', 'manager', 'cashier'],
        'view_products' => ['admin', 'manager', 'cashier'],
        'manage_products' => ['admin', 'manager'],
        'view_categories' => ['admin', 'manager'],
        'manage_categories' => ['admin', 'manager'],
        'view_pos' => ['admin', 'manager', 'cashier'],
        'manage_sales' => ['admin', 'manager', 'cashier'],
        'view_sales' => ['admin', 'manager'],
        'view_returns' => ['admin', 'manager', 'cashier'],
        'manage_returns' => ['admin', 'manager', 'cashier'],
        'view_reports' => ['admin', 'manager'],
        'view_inventory' => ['admin', 'manager', 'cashier'],
        'manage_inventory' => ['admin', 'manager'],
        'view_customers' => ['admin', 'manager'],
        'manage_customers' => ['admin', 'manager'],
        'view_users' => ['admin'],
        'manage_users' => ['admin'],
        'view_settings' => ['admin'],
        'manage_settings' => ['admin'],
        'view_expenses' => ['admin', 'manager'],
        'manage_expenses' => ['admin', 'manager'],
        'view_import' => ['admin'],
        'manage_import' => ['admin'],
        'manage_devices' => ['admin'],
    ],
];
