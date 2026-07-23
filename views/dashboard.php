<?php $device = getCurrentDevice(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('dashboard') ?></h4>
    <?php if ($device): ?>
    <span class="badge badge-secondary" style="font-size: 14px; padding: 8px 16px;">
        <i class="fas fa-desktop"></i> <?= htmlspecialchars($device['device_name']) ?>
    </span>
    <?php endif; ?>
</div>
<div class="dashboard-stats">
    <div class="stat-card primary">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h6><?= __('today_sales') ?></h6>
            <h2><?= formatPrice($stats['today_sales']) ?></h2>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-info">
            <h6><?= __('today_orders') ?></h6>
            <h2><?= $stats['today_orders'] ?></h2>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
        <div class="stat-info">
            <h6><?= __('total_products') ?></h6>
            <h2><?= $stats['total_products'] ?></h2>
        </div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h6><?= __('low_stock_alert') ?></h6>
            <h2><?= $stats['low_stock'] ?></h2>
        </div>
    </div>
</div>

<div class="card fade-in">
    <div class="card-body text-center" style="padding: 50px 30px;">
        <div style="font-size: 60px; color: rgba(99, 102, 241, 0.15); margin-bottom: 20px;">
            <i class="fas fa-store"></i>
        </div>
        <h4 style="font-weight: 700; color: var(--dark); margin-bottom: 8px;">
            <?= __('welcome_to_pos') ?>
        </h4>
        <p style="color: var(--gray); max-width: 400px; margin: 0 auto 24px;">
            <?= __('start_selling') ?>
        </p>
        <a href="?route=pos" class="btn btn-primary">
            <i class="fas fa-cash-register"></i> <?= __('start_selling_button') ?>
        </a>
    </div>
</div>
