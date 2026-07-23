<?php
// Include language functions (if not already loaded)
if (!function_exists('__')) {
    require_once __DIR__ . '/../../src/Functions.php';
}
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLanguage() ?>" dir="<?= $_SESSION['dir'] ?? 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('access_denied') ?></title>
    <link rel="stylesheet" href="/pos/public/assets/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #fff;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 40px;
        }
        .container .icon {
            font-size: 80px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        .container h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .container p {
            color: rgba(255,255,255,0.6);
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            background: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn:hover {
            background: #5a52d5;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="fas fa-lock"></i></div>
        <h1><?= __('access_denied') ?></h1>
        <p><?= __('access_denied_message') ?></p>
        <a href="?route=dashboard" class="btn"><i class="fas fa-arrow-left"></i> <?= __('go_to_dashboard') ?></a>
    </div>
</body>
</html>