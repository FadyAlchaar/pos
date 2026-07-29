<?php 
$device = getCurrentDevice(); 
$user_id = $_SESSION['user_id'] ?? 0;
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('dashboard') ?></h4>
    <?php if ($device): ?>
    <span class="badge badge-secondary" style="font-size: 14px; padding: 8px 16px;">
        <i class="fas fa-desktop"></i> <?= htmlspecialchars($device['device_name']) ?>
    </span>
    <?php endif; ?>
</div>

<!-- ===== CASH DRAWER (MOVED OUTSIDE THE FLEX HEADER) ===== -->
<?php if ($device): ?>
<div class="card mb-4" style="border-left: 4px solid #6c63ff;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-1"><i class="fas fa-cash-register" style="color: #6c63ff;"></i> <?= __('cash_drawer') ?></h5>
                <p class="mb-0">
                    <?= __('current_balance') ?>: 
                    <strong id="cashBalance" style="font-size: 20px; color: #2ecc71;">0.00</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="startShift()">
                    <i class="fas fa-play"></i> <?= __('start_shift') ?>
                </button>
                <button class="btn btn-danger btn-sm" onclick="closeShift()">
                    <i class="fas fa-stop"></i> <?= __('close_shift') ?>
                </button>
                <button class="btn btn-outline btn-sm" onclick="loadBalance()">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ===== STAT CARDS ===== -->
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

<!-- ===== WELCOME CARD ===== -->
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

<script>
// ============================================
// LOAD CURRENT CASH BALANCE
// ============================================
function loadBalance() {
    fetch('?ajax=1&action=get_cash_balance')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cashBalance').textContent = data.balance || '0.00';
            } else {
                console.warn('Failed to load cash balance:', data.message);
            }
        })
        .catch(err => console.error('Error loading cash balance:', err));
}

// ============================================
// START SHIFT
// ============================================
function startShift() {
    const amount = prompt('<?= __('enter_starting_cash') ?>', '100.00');
    if (amount === null) return;
    
    const parsedAmount = parseFloat(amount);
    if (isNaN(parsedAmount) || parsedAmount < 0) {
        alert('<?= __('Please enter a valid amount (0 or more).') ?>');
        return;
    }
    
    fetch('?ajax=1&action=start_shift', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            amount: parsedAmount, 
            csrf_token: '<?= generateCSRFToken() ?>' 
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            loadBalance(); // Refresh balance
        }
    })
    .catch(err => alert('<?= __('Network error') ?>'));
}

// ============================================
// CLOSE SHIFT
// ============================================
function closeShift() {
    if (!confirm('<?= __('close_shift_confirm') ?>')) return;
    
    fetch('?ajax=1&action=close_shift', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            csrf_token: '<?= generateCSRFToken() ?>' 
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            loadBalance(); // Refresh balance
        }
    })
    .catch(err => alert('<?= __('Network error') ?>'));
}

// ============================================
// LOAD BALANCE ON PAGE LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadBalance();
});
</script>