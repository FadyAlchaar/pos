<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS System' ?></title>
    
    <!-- Local Font Awesome -->
    <link rel="stylesheet" href="/pos/public/assets/css/all.min.css">
    
    <!-- SIMPLE CSS - Guaranteed to work -->
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .sidebar { width: 220px; background: #1a1a2e; color: #fff; position: fixed; height: 100%; padding: 20px 0; }
        .sidebar a { display: block; color: #aaa; padding: 12px 20px; text-decoration: none; }
        .sidebar a:hover { background: #333; color: #fff; }
        .sidebar a.active { background: #6c63ff; color: #fff; }
        .content { margin-left: 220px; padding: 20px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background: #6c63ff; color: #fff; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        .badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-secondary { background: #e9ecef; color: #6c757d; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="padding: 0 20px;">POS</h3>
        <a href="?route=dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="?route=products" class="<?= $active === 'products' ? 'active' : '' ?>">Products</a>
        <a href="?route=pos" class="<?= $active === 'pos' ? 'active' : '' ?>">POS</a>
        <a href="?route=sales" class="<?= $active === 'sales' ? 'active' : '' ?>">Sales</a>
        <a href="?route=logout" style="margin-top:50px;color:#e74c3c;">Logout</a>
    </div>
    
    <div class="content">
        <h1><?= $page_title ?? 'Dashboard' ?></h1>
        <?= $content ?? '' ?>
    </div>
</body>
</html>