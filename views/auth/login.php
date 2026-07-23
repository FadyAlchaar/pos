<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('pos_login') ?></title>
    <!-- Local Font Awesome -->
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
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: #f59e0b;
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
            opacity: 0.1;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 45px 40px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header { text-align: center; margin-bottom: 35px; }
        .login-header .logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: #fff;
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        }
        .login-header h2 { color: #fff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; }
        .login-header p { color: rgba(255, 255, 255, 0.5); font-size: 14px; margin-top: 4px; }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 500;
        }
        .form-group label i { margin-right: 8px; color: #818cf8; }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.3); }
        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4); }
        .btn-primary i { margin-left: 8px; transition: 0.3s; }
        .btn-primary:hover i { transform: translateX(4px); }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 13px;
        }
        .login-footer strong { color: rgba(255, 255, 255, 0.6); }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        @media (max-width: 480px) {
            .login-box { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="fas fa-store-alt"></i>
                </div>
                <h2><?= __('pos_system') ?></h2>
                <p><?= __('sign_in_to_manage_your_store') ?></p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?route=login">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <div class="form-group">
                    <label><i class="fas fa-user"></i> <?= __('username') ?></label>
                    <input type="text" name="username" class="form-control" placeholder="<?= __('enter_your_username') ?>" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> <?= __('password') ?></label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn-primary">
                    <?= __('sign_in') ?> <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <!-- <?= __('default') ?>: <strong>admin</strong> / <strong>admin123</strong> -->
            </div>
        </div>
    </div>
</body>
</html>