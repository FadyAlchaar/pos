<?php
// Load current permissions config
$permissionsConfig = require __DIR__ . '/../config/permissions.php';
$roles = $permissionsConfig['roles'];
$allPermissions = $permissionsConfig['permissions'];

// Group permissions by category
$categories = [
    'Dashboard' => ['view_dashboard'],
    'Products' => ['view_products', 'manage_products'],
    'Categories' => ['view_categories', 'manage_categories'],
    'POS' => ['view_pos', 'manage_sales'],
    'Sales' => ['view_sales'],
    'Returns' => ['view_returns', 'manage_returns'],
    'Reports' => ['view_reports'],
    'Inventory' => ['view_inventory', 'manage_inventory'],
    'Customers' => ['view_customers', 'manage_customers'],
    'Users' => ['view_users', 'manage_users'],
    'Settings' => ['view_settings', 'manage_settings'],
    'Expenses' => ['view_expenses', 'manage_expenses'],
    'Import' => ['view_import', 'manage_import'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('permissions_management') ?></h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <?= __('permissions_instructions') ?>
        </div>

        <form id="permissionsForm" onsubmit="savePermissions(event)">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="table-responsive">
                <table class="table table-bordered" id="permissionsTable">
                    <thead>
                        <tr>
                            <th style="width: 200px;"><?= __('permission') ?></th>
                            <?php foreach ($roles as $roleName => $roleLevel): ?>
                                <th class="text-center"><?= ucfirst($roleName) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category => $perms): ?>
                            <tr class="table-secondary">
                                <td colspan="<?= count($roles) + 1 ?>"><strong><?= $category ?></strong></td>
                            </tr>
                            <?php foreach ($perms as $perm): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary"><?= $perm ?></span>
                                        <br><small class="text-muted"><?= __($perm) ?></small>
                                    </td>
                                    <?php foreach ($roles as $roleName => $roleLevel): ?>
                                        <td class="text-center">
                                            <input type="checkbox" 
                                                   name="permissions[<?= $perm ?>][<?= $roleName ?>]" 
                                                   value="1"
                                                   <?= in_array($roleName, $allPermissions[$perm] ?? []) ? 'checked' : '' ?>
                                                   class="permission-checkbox"
                                                   data-permission="<?= $perm ?>"
                                                   data-role="<?= $roleName ?>">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= __('save_permissions') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="location.reload()">
                    <i class="fas fa-sync"></i> <?= __('refresh') ?>
                </button>
            </div>
        </form>

        <div id="permissionsMessage" style="display:none; margin-top: 20px;"></div>
    </div>
</div>

<script>
function savePermissions(e) {
    e.preventDefault();
    const form = document.getElementById('permissionsForm');
    const formData = new FormData(form);
    formData.append('action', 'update_permissions');
    
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('saving') ?>...';
    btn.disabled = true;
    
    fetch('?ajax=1&action=update_permissions', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('permissionsMessage');
        msg.style.display = 'block';
        if (data.success) {
            msg.innerHTML = `<div class="alert alert-success">✅ ${data.message}</div>`;
            setTimeout(() => location.reload(), 1500);
        } else {
            msg.innerHTML = `<div class="alert alert-danger">❌ ${data.message}</div>`;
        }
    })
    .catch(err => {
        alert('Network error: ' + err);
    })
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <?= __('save_permissions') ?>';
        btn.disabled = false;
    });
}
</script>