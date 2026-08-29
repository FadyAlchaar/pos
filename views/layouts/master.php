<!DOCTYPE html>
<html lang="<?= getCurrentLanguage() ?>" dir="<?= $_SESSION['dir'] ?? 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? __('pos_system') ?></title>
    
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="/pos/public/assets/css/all.min.css">    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/pos/public/assets/css/style.css">
    
    <script src="/pos/public/assets/js/app.js"></script>
    <script src="/pos/public/assets/js/chart.min.js"></script>
    <style>
        /* ALL STYLES ARE HERE - NO EXTERNAL FILE NEEDED */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; height: 100vh; overflow: hidden; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; background: #1a1a2e; height: 100vh; position: fixed; left: 0; top: 0; display: flex; flex-direction: column; padding: 20px 0; transition: transform 0.3s ease; z-index: 1000; }
        .sidebar .brand { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand h3 { color: #fff; font-size: 22px; }
        .sidebar .brand h3 i { color: #6c63ff; margin-right: 8px; }
        .sidebar .brand small { color: rgba(255,255,255,0.4); font-size: 12px; }
        .sidebar-nav { flex: 1; padding: 20px 15px; overflow-y: auto; }
        .sidebar-nav a { display: block; padding: 12px 18px; color: rgba(255,255,255,0.6); text-decoration: none; border-radius: 8px; margin-bottom: 4px; font-size: 14px; transition: 0.3s; }
        .sidebar-nav a i { width: 20px; margin-right: 12px; text-align: center; }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar-nav a.active { background: #6c63ff; color: #fff; }
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar-footer .lang-btn { width: 100%; padding: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); border-radius: 8px; cursor: pointer; font-size: 13px; }
        .sidebar-footer .lang-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar-footer .logout-btn { display: block; text-align: center; margin-top: 10px; padding: 10px; color: rgba(255,255,255,0.4); text-decoration: none; border-radius: 8px; font-size: 13px; }
        .sidebar-footer .logout-btn:hover { background: rgba(231,76,60,0.2); color: #e74c3c; }
        
        /* CONTENT */
        .content { margin-left: 250px; height: 100vh; display: flex; flex-direction: column; background: #f4f6f9; transition: margin-left 0.3s ease; }
        
        /* HEADER */
        .header { background: #fff; padding: 15px 30px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .header h1 { font-size: 20px; font-weight: 600; color: #1a1a2e; margin: 0; }
        .header .hamburger { display: none; background: none; border: none; font-size: 22px; cursor: pointer; padding: 4px 8px; color: #1a1a2e; }
        .header .user-info { display: flex; align-items: center; gap: 12px; }
        .header .user-info .avatar { width: 36px; height: 36px; background: #6c63ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 600; }
        .header .user-info .name { font-weight: 600; font-size: 14px; color: #1a1a2e; }
        .header .user-info .role { font-size: 12px; color: #95a5a6; }

        /* Toggle button in header - always visible */
        #sidebarToggleBtn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #1a1a2e;
            padding: 0 6px;
        }
        #sidebarToggleBtn:hover {
            color: #6c63ff;
        }
        
        /* PAGE CONTENT */
        .page-content { flex: 1; padding: 25px 30px; overflow-y: auto; }
        
        /* CARDS */
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); overflow: hidden; }
        .card .card-header { padding: 18px 24px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; }
        .card .card-header h5 { font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 0; }
        .card .card-body { padding: 24px; }
        
        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; font-family: inherit; text-decoration: none; }
        .btn-primary { background: #6c63ff; color: #fff; }
        .btn-primary:hover { background: #5a52d5; transform: translateY(-2px); }
        .btn-success { background: #2ecc71; color: #fff; }
        .btn-success:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-danger:hover { background: #c0392b; }
        .btn-outline { background: transparent; color: #1a1a2e; border: 1px solid #ddd; }
        .btn-outline:hover { background: #f4f6f9; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        
        /* TABLE */
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .table thead th { padding: 14px 16px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; color: #95a5a6; border-bottom: 2px solid #e9ecef; }
        .table tbody td { padding: 14px 16px; border-bottom: 1px solid #e9ecef; color: #1a1a2e; }
        .table tbody tr:hover { background: #f8f9fa; }
        
        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #d5f5e3; color: #27ae60; }
        .badge-danger { background: #fadbd8; color: #e74c3c; }
        .badge-warning { background: #fdebd0; color: #f39c12; }
        .badge-secondary { background: #e9ecef; color: #95a5a6; }
        
        /* FORMS */
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px; color: #1a1a2e; }
        .form-group label i { color: #95a5a6; margin-right: 6px; }
        .form-control { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #6c63ff; box-shadow: 0 0 0 3px rgba(108,99,255,0.1); }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2395a5a6' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; }
        
        /* STAT CARDS */
        .dashboard-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px 24px; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .stat-card .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #fff; }
        .stat-card.primary .stat-icon { background: #6c63ff; }
        .stat-card.success .stat-icon { background: #2ecc71; }
        .stat-card.warning .stat-icon { background: #f39c12; }
        .stat-card.danger .stat-icon { background: #e74c3c; }
        .stat-card .stat-info h6 { color: #95a5a6; font-size: 13px; margin: 0 0 4px; }
        .stat-card .stat-info h2 { margin: 0; font-size: 24px; color: #1a1a2e; }
        
        /* MODAL */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 9999; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal-content { background: #fff; border-radius: 16px; max-width: 550px; width: 90%; padding: 30px; max-height: 90vh; overflow-y: auto; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-close { background: none; border: none; font-size: 24px; color: #95a5a6; cursor: pointer; }
        .modal-close:hover { color: #1a1a2e; }
        
        /* FOOTER */
        .footer { background: #fff; padding: 12px 30px; border-top: 1px solid #e9ecef; text-align: center; font-size: 13px; color: #95a5a6; flex-shrink: 0; }
        
        /* ===== SIDEBAR TOGGLE STATES ===== */
        body.sidebar-hidden .sidebar {
            transform: translateX(-100%);
        }
        body.sidebar-hidden .content {
            margin-left: 0;
        }
        [dir="rtl"] body.sidebar-hidden .sidebar {
            transform: translateX(100%);
        }
        [dir="rtl"] body.sidebar-hidden .content {
            margin-right: 0;
        }

        #sidebarToggleBtn {
            display: inline-block;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
            }
            .header .hamburger {
                display: block;
            }
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            .header .user-info .user-details {
                display: none;
            }
            #sidebarToggleBtn {
                display: none; /* On mobile, the hamburger is enough */
            }
            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }
            [dir="rtl"] .sidebar.open {
                transform: translateX(0);
            }
            [dir="rtl"] .content {
                margin-right: 0;
            }
        }
        
        [dir="rtl"] .sidebar { left: auto; right: 0; }
        [dir="rtl"] .content { margin-left: 0; margin-right: 250px; }
        
        .text-center { text-align: center; }
        .text-muted { color: #95a5a6; }
        .mt-3 { margin-top: 16px; }
        .mb-0 { margin-bottom: 0; }
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 16px; }
        .flex-wrap { flex-wrap: wrap; }
        .w-100 { width: 100%; }
        /* ===== SIDEBAR NAVIGATION GROUPS ===== */
        .sidebar-nav .nav-group {
            margin-bottom: 8px;
        }

        .sidebar-nav .nav-group-label {
            color: rgba(255, 255, 255, 0);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 16px 6px;
            font-weight: 600;
            pointer-events: none;
        }

        .sidebar-nav .nav-group a {
            display: block;
            padding: 10px 18px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 2px;
            font-size: 14px;
            transition: 0.3s;
        }

        .sidebar-nav .nav-group a i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar-nav .nav-group a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .sidebar-nav .nav-group a.active {
            background: #6c63ff;
            color: #fff;
        }

        /* ===== COLLAPSIBLE SIDEBAR GROUPS ===== */
        .sidebar-nav .nav-group {
            margin-bottom: 4px;
        }

        .sidebar-nav .nav-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px 6px;
            cursor: pointer;
            user-select: none;
            transition: 0.2s;
            border-radius: 6px;
        }

        .sidebar-nav .nav-group-header:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav .nav-group-label {
            color: rgb(255, 255, 255);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 600;
            pointer-events: none;
        }

        .sidebar-nav .nav-group-toggle {
            color: rgba(255, 255, 255, 0.25);
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-nav .nav-group.collapsed .nav-group-toggle {
            transform: rotate(-90deg);
        }

        .sidebar-nav .nav-group-items {
            overflow: hidden;
            max-height: 500px;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .sidebar-nav .nav-group.collapsed .nav-group-items {
            max-height: 0;
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-nav .nav-group-items a {
            display: block;
            padding: 8px 18px 8px 38px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 1px;
            font-size: 13px;
            transition: 0.3s;
        }

        .sidebar-nav .nav-group-items a i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar-nav .nav-group-items a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .sidebar-nav .nav-group-items a.active {
            background: #6c63ff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <h3><i class="fas fa-store"></i> <?= __('pos') ?></h3>
                <small><?= __('point_of_sale') ?></small>
            </div>
            
            <nav class="sidebar-nav">
                <?php if (hasPermission('view_dashboard')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="dashboard">
                        <span class="nav-group-label"><?= __('dashboard') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <a href="?route=dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-th-large"></i> <?= __('dashboard') ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_pos')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="pos">
                        <span class="nav-group-label"><?= __('pos') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <a href="?route=pos" class="<?= $active === 'pos' ? 'active' : '' ?>">
                            <i class="fas fa-cash-register"></i> <?= __('pos') ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_customers') || hasPermission('view_inventory')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="contacts">
                        <span class="nav-group-label"><?= __('contacts') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <?php if (hasPermission('view_customers')): ?>
                        <a href="?route=customers" class="<?= $active === 'customers' ? 'active' : '' ?>">
                            <i class="fas fa-users"></i> <?= __('customers') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_inventory')): ?>
                        <a href="?route=suppliers" class="<?= $active === 'suppliers' ? 'active' : '' ?>">
                            <i class="fas fa-truck"></i> <?= __('suppliers') ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_products') || hasPermission('view_categories')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="items">
                        <span class="nav-group-label"><?= __('items') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <?php if (hasPermission('view_products')): ?>
                        <a href="?route=products" class="<?= $active === 'products' ? 'active' : '' ?>">
                            <i class="fas fa-boxes"></i> <?= __('products') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_categories')): ?>
                        <a href="?route=categories" class="<?= $active === 'categories' ? 'active' : '' ?>">
                            <i class="fas fa-tags"></i> <?= __('categories') ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_sales') || hasPermission('view_returns') || hasPermission('view_inventory')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="transactions">
                        <span class="nav-group-label"><?= __('transactions') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <?php if (hasPermission('view_sales')): ?>
                        <a href="?route=sales" class="<?= $active === 'sales' ? 'active' : '' ?>">
                            <i class="fas fa-shopping-cart"></i> <?= __('sales') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_returns')): ?>
                        <a href="?route=returns" class="<?= $active === 'returns' ? 'active' : '' ?>">
                            <i class="fas fa-undo-alt"></i> <?= __('returns') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_inventory')): ?>
                        <a href="?route=transfers" class="<?= $active === 'transfers' ? 'active' : '' ?>">
                            <i class="fas fa-exchange-alt"></i> <?= __('transfers') ?>
                        </a>
                        <a href="?route=stock_adjustment" class="<?= $active === 'stock_adjustment' ? 'active' : '' ?>">
                            <i class="fas fa-edit"></i> <?= __('stock_adjustment') ?>
                        </a>
                        <a href="?route=purchase_orders" class="<?= $active === 'purchase_orders' ? 'active' : '' ?>">
                            <i class="fas fa-shopping-cart"></i> <?= __('purchase_orders') ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_reports') || hasPermission('view_inventory')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="reports">
                        <span class="nav-group-label"><?= __('reports') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <?php if (hasPermission('view_reports')): ?>
                        <a href="?route=reports" class="<?= $active === 'reports' ? 'active' : '' ?>">
                            <i class="fas fa-chart-bar"></i> <?= __('reports') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_inventory')): ?>
                        <a href="?route=inventory" class="<?= $active === 'inventory' ? 'active' : '' ?>">
                            <i class="fas fa-warehouse"></i> <?= __('inventory') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_reports')): ?>
                        <a href="?route=cash_report" class="<?= $active === 'cash_report' ? 'active' : '' ?>">
                            <i class="fas fa-money-bill-wave"></i> <?= __('cash_report') ?>
                        </a>
                        <a href="?route=stock_movement" class="<?= $active === 'stock_movement' ? 'active' : '' ?>">
                            <i class="fas fa-exchange-alt"></i> <?= t('Stock Movement', 'حركة المخزون') ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (hasPermission('view_expenses')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="misc">
                        <span class="nav-group-label"><?= __('miscellaneous') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <a href="?route=expenses" class="<?= $active === 'expenses' ? 'active' : '' ?>">
                            <i class="fas fa-money-bill-wave"></i> <?= __('expenses') ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isAdmin() || hasPermission('view_settings') || hasPermission('view_users') || hasPermission('manage_devices') || hasPermission('view_import')): ?>
                <div class="nav-group">
                    <div class="nav-group-header" data-group="settings">
                        <span class="nav-group-label"><?= __('settings') ?></span>
                        <i class="fas fa-chevron-down nav-group-toggle"></i>
                    </div>
                    <div class="nav-group-items">
                        <?php if (isAdmin()): ?>
                        <a href="?route=devices" class="<?= $active === 'devices' ? 'active' : '' ?>">
                            <i class="fas fa-server"></i> <?= __('devices_management') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_users')): ?>
                        <a href="?route=users" class="<?= $active === 'users' ? 'active' : '' ?>">
                            <i class="fas fa-users-cog"></i> <?= __('users') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('manage_users')): ?>
                        <a href="?route=permissions" class="<?= $active === 'permissions' ? 'active' : '' ?>">
                            <i class="fas fa-lock"></i> <?= __('permissions_management') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_settings')): ?>
                        <a href="?route=settings" class="<?= $active === 'settings' ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i> <?= __('settings') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (hasPermission('view_import')): ?>
                        <a href="?route=import" class="<?= $active === 'import' ? 'active' : '' ?>">
                            <i class="fas fa-file-import"></i> <?= __('import') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                        <a href="?route=backup" class="<?= $active === 'backup' ? 'active' : '' ?>">
                            <i class="fas fa-database"></i> <?= __('backup') ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <button class="lang-btn" onclick="switchLanguage()">
                    <i class="fas fa-globe"></i> 
                    <?= getCurrentLanguage() === 'ar' ? 'English' : 'العربية' ?>
                </button>
                <a href="?route=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> <?= __('logout') ?>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="content">
            <!-- Header -->
            <header class="header">
                <div class="header-left" style="display:flex;align-items:center;gap:10px;">
                    <button class="hamburger">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <i class="fas fa-chevron-left" id="sidebarToggleIcon"></i>
                    </button>
                    <h1><?= $page_title ?? __('dashboard') ?></h1>
                </div>
                <div class="header-right" style="display:flex;align-items:center;gap:15px;">
                    <?php if (isAdmin()): ?>
                    <div class="device-switcher" style="display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-desktop" style="color: #6c63ff; font-size: 16px;"></i>
                        <form method="POST" action="?route=switch-device" style="display:inline;">
                            <select name="device_id" onchange="switchDevice(this.value)" class="form-control" style="min-width: 140px; display: inline-block; height: 38px; font-size: 14px; font-weight: 500; padding: 5px 30px 5px 12px; border-radius: 8px; border: 1px solid #ddd; background-color: #fff; cursor: pointer;">
                                <?php foreach (getDevices() as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ($_SESSION['device_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d['device_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    <?php endif; ?>
                    <div class="user-info">
                        <div class="avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'G', 0, 1)) ?></div>
                        <div class="user-details">
                            <div class="name"><?= $_SESSION['user_name'] ?? __('guest') ?></div>
                            <div class="role"><?= $_SESSION['user_role'] ?? '' ?></div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="page-content">
                <?= $content ?? '' ?>
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                &copy; <?= date('Y') ?> POS System. <?= __('all_rights_reserved') ?>
            </footer>
        </main>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('sidebarToggleIcon');
            sidebar.classList.toggle('open');
            document.body.classList.toggle('sidebar-hidden');
            if (icon) {
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
            }
        }
        
        function switchLanguage() {
            fetch('?route=switch-lang', { 
                method: 'POST', 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(() => location.reload());
        }
        // ============================================
        // CLOSE MODAL ON ESCAPE KEY
        // ============================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Find all open modals and close them
                document.querySelectorAll('.modal-overlay.show').forEach(function(modal) {
                    // Try to call the close function if it exists
                    const closeBtn = modal.querySelector('.modal-close');
                    if (closeBtn) {
                        closeBtn.click();
                    } else {
                        // Fallback: just remove the show class
                        modal.classList.remove('show');
                    }
                });
            }
        });
        const currencySymbol = '<?= getCurrencySymbol() ?>';
    
        function formatPrice(amount) {
            // Ensure amount is a number
            amount = parseFloat(amount) || 0;
            // Format with thousand separators
            const formatted = amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            const isRtl = document.documentElement.dir === 'rtl';
            if (isRtl) {
                // Arabic: symbol on the left
                return currencySymbol + ' ' + formatted;
            } else {
                // English: symbol on the right
                return formatted + ' ' + currencySymbol;
            }
        }

        // ============================================
        // SORTABLE TABLES
        // Add class="sortable-table" to any <table> and its columns become
        // click-to-sort automatically — no per-page JS needed. Add
        // data-no-sort to a <th> to exclude one column (e.g. an Actions
        // column with buttons). Works on both server-rendered tables and
        // tables whose rows get replaced by an AJAX refresh, since sorting
        // reads whatever is currently in the <tbody> at click time rather
        // than caching data up front.
        // ============================================
        function initSortableTables() {
            document.querySelectorAll('table.sortable-table').forEach(function(table) {
                const headerRow = table.querySelector('thead tr');
                if (!headerRow || headerRow.dataset.sortableBound === '1') return;
                headerRow.dataset.sortableBound = '1';

                Array.from(headerRow.children).forEach(function(th, colIndex) {
                    if (th.hasAttribute('data-no-sort')) return;
                    th.classList.add('sortable-col');
                    th.style.cursor = 'pointer';
                    th.style.userSelect = 'none';

                    const indicator = document.createElement('span');
                    indicator.className = 'sort-indicator';
                    indicator.style.marginLeft = '4px';
                    indicator.style.opacity = '0.4';
                    indicator.textContent = '⇅';
                    th.appendChild(indicator);

                    th.addEventListener('click', function() {
                        sortTableByColumn(table, headerRow, colIndex, indicator);
                    });
                });
            });
        }

        function parseSortValue(text) {
            text = (text || '').trim();
            const numericCandidate = text.replace(/[^\d.\-]/g, '');
            if (numericCandidate !== '' && /^-?\d+(\.\d+)?$/.test(numericCandidate)) {
                return { type: 'number', value: parseFloat(numericCandidate) };
            }
            return { type: 'string', value: text.toLowerCase() };
        }

        function sortTableByColumn(table, headerRow, colIndex, indicator) {
            const tbody = table.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            if (rows.length === 0) return;

            // Toggle direction; reset every other column's indicator first.
            const currentDir = indicator.dataset.dir === 'asc' ? 'asc' : null;
            headerRow.querySelectorAll('.sort-indicator').forEach(function(ind) {
                ind.textContent = '⇅';
                ind.style.opacity = '0.4';
                delete ind.dataset.dir;
            });
            const newDir = currentDir === 'asc' ? 'desc' : 'asc';
            indicator.dataset.dir = newDir;
            indicator.textContent = newDir === 'asc' ? '▲' : '▼';
            indicator.style.opacity = '1';

            rows.sort(function(rowA, rowB) {
                const cellA = rowA.children[colIndex];
                const cellB = rowB.children[colIndex];
                const textA = cellA ? (cellA.dataset.sortValue ?? cellA.textContent) : '';
                const textB = cellB ? (cellB.dataset.sortValue ?? cellB.textContent) : '';
                const a = parseSortValue(textA);
                const b = parseSortValue(textB);
                let result = (a.type === 'number' && b.type === 'number')
                    ? a.value - b.value
                    : a.value.localeCompare(b.value);
                return newDir === 'asc' ? result : -result;
            });

            rows.forEach(function(row) { tbody.appendChild(row); });
        }

        document.addEventListener('DOMContentLoaded', initSortableTables);
        // Re-scan after any AJAX-driven table refresh across the app.
        document.addEventListener('table-content-updated', initSortableTables);

        function switchDevice(deviceId) {
            if (!deviceId) return;
            fetch('?route=switch-device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ device_id: deviceId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Reload the page without losing scroll position
                    location.reload();
                }
            })
            .catch(err => console.error('Device switch error:', err));
        }
        // Preserve scroll position across page reloads
        document.addEventListener('DOMContentLoaded', function() {
            const scrollPos = sessionStorage.getItem('scrollPos');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('scrollPos');
            }
        });

        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('scrollPos', window.scrollY);
        });

                // ============================================
        // COLLAPSIBLE SIDEBAR GROUPS
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            const groups = document.querySelectorAll('.nav-group');

            groups.forEach(group => {
                const header = group.querySelector('.nav-group-header');
                const groupName = header.dataset.group;

                // Restore state from localStorage
                const savedState = localStorage.getItem('sidebar_group_' + groupName);
                if (savedState === 'collapsed') {
                    group.classList.add('collapsed');
                }

                // Toggle on click
                header.addEventListener('click', function(e) {
                    e.stopPropagation();
                    group.classList.toggle('collapsed');
                    const isCollapsed = group.classList.contains('collapsed');
                    localStorage.setItem('sidebar_group_' + groupName, isCollapsed ? 'collapsed' : 'expanded');
                });
            });
        });

    </script>
</body>
</html>