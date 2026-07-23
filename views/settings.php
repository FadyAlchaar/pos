<?php
$settings = getSettings();
$printerSettings = getPrinterSettings();
$categories = ['Rent', 'Utilities', 'Salaries', 'Supplies', 'Maintenance', 'Marketing', 'Transportation', 'Insurance', 'Other'];
$currentCategories = getSetting('expense_categories');
$expenseCategories = $currentCategories ? explode(',', $currentCategories) : $categories;
$canManage = hasPermission('manage_settings');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('settings') ?></h4>
</div>

    <!-- ===== STORE INFORMATION ===== -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-store"></i> <?= __('store_information') ?></h5>
            </div>
            <div class="card-body">
                <form id="settingsForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div class="form-group">
                        <label><i class="fas fa-store-alt"></i> <?= __('store_name') ?></label>
                        <input type="text" id="store_name" name="store_name" class="form-control" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> <?= __('store_address') ?></label>
                        <input type="text" id="store_address" name="store_address" class="form-control" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> <?= __('store_phone') ?></label>
                        <input type="text" id="store_phone" name="store_phone" class="form-control" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> <?= __('store_email') ?></label>
                        <input type="email" id="store_email" name="store_email" class="form-control" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

    <!-- ===== SYSTEM SETTINGS ===== -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i> <?= __('system_settings') ?></h5>
            </div>
            <div class="card-body">
                <form id="systemSettingsForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> <?= __('currency_symbol') ?></label>
                        <input type="text" id="currency_symbol" name="currency_symbol" class="form-control" value="<?= htmlspecialchars($settings['currency_symbol'] ?? 'ل.س') ?>" maxlength="10">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-percent"></i> <?= __('tax_rate') ?></label>
                        <input type="number" step="0.01" id="tax_rate" name="tax_rate" class="form-control" value="<?= htmlspecialchars($settings['tax_rate'] ?? '0.00') ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-globe"></i> <?= __('default_language') ?></label>
                        <select id="default_language" name="default_language" class="form-control">
                            <option value="en" <?= ($settings['default_language'] ?? 'en') === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="ar" <?= ($settings['default_language'] ?? 'en') === 'ar' ? 'selected' : '' ?>>العربية</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> <?= __('receipt_footer') ?></label>
                        <textarea id="receipt_footer" name="receipt_footer" class="form-control" rows="3"><?= htmlspecialchars($settings['receipt_footer'] ?? 'Thank you for your business!') ?></textarea>
                    </div>
                </form>
            </div>
        </div>

    <!-- ===== PRINTER SETTINGS ===== -->
     <?php if ($canManage): ?>
    <div class="col-md-12 mt-4">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-print"></i> <?= __('printer_configuration') ?></h5>
            </div>
            <div class="card-body">
                <form id="printerSettingsForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-print"></i> <?= __('printer_type') ?></label>
                                <select id="printer_type" name="printer_type" class="form-control" onchange="togglePrinterFields()">
                                    <option value="normal" <?= ($settings['printer_type'] ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normal Printer (Browser)</option>
                                    <option value="usb" <?= ($settings['printer_type'] ?? 'normal') === 'usb' ? 'selected' : '' ?>>USB Thermal Printer</option>
                                    <option value="network" <?= ($settings['printer_type'] ?? 'normal') === 'network' ? 'selected' : '' ?>>Network Thermal Printer</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="printerNameGroup">
                            <label><i class="fas fa-print"></i> <?= __('windows_printer_name') ?></label>
                            <select id="printer_name" name="printer_name" class="form-control">
                                <option value=""><?= __('loading_printers') ?>...</option>
                            </select>
                            <small class="text-muted"><?= __('select_from_installed_printers') ?></small>
                        </div>
                        <div class="col-md-4" id="printerNetworkGroup" style="display:none;">
                            <div class="form-group">
                                <label><i class="fas fa-network-wired"></i> <?= __('printer_ip') ?></label>
                                <input type="text" id="printer_ip" name="printer_ip" class="form-control" placeholder="192.168.1.100" value="<?= htmlspecialchars($settings['printer_ip'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-plug"></i> <?= __('printer_port') ?></label>
                                <input type="text" id="printer_port" name="printer_port" class="form-control" placeholder="9100" value="<?= htmlspecialchars($settings['printer_port'] ?? '9100') ?>">
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-code"></i> C# Bridge Executable Path</label>
                                <input type="text" id="printer_bridge_path" name="printer_bridge_path" class="form-control" 
                                    placeholder="C:\POS\TextPrinter.exe" 
                                    value="<?= htmlspecialchars($settings['printer_bridge_path'] ?? 'C:\\POS\\TextPrinter.exe') ?>">
                                <small class="text-muted">Full path to the compiled TextPrinter.exe (supports Arabic fonts on thermal printers).</small>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-print"></i> Auto-print after checkout</label>
                                <select id="auto_print" name="auto_print" class="form-control">
                                    <option value="1" <?= ($settings['auto_print'] ?? '1') == '1' ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= ($settings['auto_print'] ?? '1') == '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><i class="fas fa-copy"></i> <?= __('receipt_copies') ?></label>
                                <select id="receipt_copies" name="receipt_copies" class="form-control">
                                    <?php for ($i = 1; $i <= 3; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($settings['receipt_copies'] ?? 1) == $i ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== EXPENSE CATEGORIES ===== -->
    <div class="col-md-12 mt-4">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tags"></i> <?= __('expense_categories') ?></h5>
                <button class="btn btn-sm btn-primary" onclick="addExpenseCategory()">
                    <i class="fas fa-plus"></i> <?= __('add_category') ?>
                </button>
            </div>
            <div class="card-body">
                <form id="expenseCategoriesForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="expense_categories" id="expenseCategoriesInput" value="<?= htmlspecialchars(implode(',', $expenseCategories)) ?>">
                    <div id="expenseCategoriesList" class="d-flex flex-wrap gap-2">
                        <?php foreach ($expenseCategories as $cat): ?>
                            <span class="badge badge-primary" style="padding: 8px 12px; font-size: 14px;">
                                <?= htmlspecialchars($cat) ?>
                                <button type="button" class="btn btn-sm btn-danger ms-1" onclick="removeExpenseCategory('<?= htmlspecialchars($cat) ?>')" style="padding: 0 4px; font-size: 12px; background: none; border: none; color: #fff;">&times;</button>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== SAVE ALL SETTINGS ===== -->
    <div class="col-md-12 mt-4">
        <div class="card fade-in">
            <div class="card-body text-center">
                <button class="btn btn-primary btn-lg" onclick="saveAllSettings()">
                    <i class="fas fa-save"></i> <?= __('save_all_settings') ?>
                </button>
                <button class="btn btn-outline btn-lg ms-2" onclick="location.reload()">
                    <i class="fas fa-sync"></i> <?= __('refresh') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MESSAGE ===== -->
<div id="settingsMessage" style="display:none; margin-top: 20px;"></div>

<script>
// ============================================
// TOGGLE PRINTER FIELDS
// ============================================
function togglePrinterFields() {
    const type = document.getElementById('printer_type').value;
    document.getElementById('printerNameGroup').style.display = (type === 'usb') ? 'block' : 'none';
    document.getElementById('printerNetworkGroup').style.display = (type === 'network') ? 'flex' : 'none';
}

// Initial toggle
document.addEventListener('DOMContentLoaded', togglePrinterFields);

// ============================================
// EXPENSE CATEGORIES
// ============================================
let expenseCategories = [];

function loadExpenseCategories() {
    const input = document.getElementById('expenseCategoriesInput');
    const list = document.getElementById('expenseCategoriesList');
    const cats = input.value ? input.value.split(',') : [];
    expenseCategories = cats;
    renderExpenseCategories();
}

function renderExpenseCategories() {
    const list = document.getElementById('expenseCategoriesList');
    list.innerHTML = expenseCategories.map(cat => `
        <span class="badge badge-primary" style="padding: 8px 12px; font-size: 14px;">
            ${escapeHtml(cat)}
            <button type="button" class="btn btn-sm btn-danger ms-1" onclick="removeExpenseCategory('${escapeHtml(cat)}')" style="padding: 0 4px; font-size: 12px; background: none; border: none; color: #fff;">&times;</button>
        </span>
    `).join('');
    document.getElementById('expenseCategoriesInput').value = expenseCategories.join(',');
}

function addExpenseCategory() {
    const name = prompt('Enter new expense category name:');
    if (!name || name.trim() === '') return;
    const trimmed = name.trim();
    if (expenseCategories.includes(trimmed)) {
        alert('Category already exists.');
        return;
    }
    expenseCategories.push(trimmed);
    renderExpenseCategories();
}

function removeExpenseCategory(cat) {
    if (!confirm(`Remove category "${cat}"?`)) return;
    expenseCategories = expenseCategories.filter(c => c !== cat);
    renderExpenseCategories();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// SAVE ALL SETTINGS
// ============================================
function saveAllSettings() {
    // Collect all form data
    const forms = ['settingsForm', 'systemSettingsForm', 'printerSettingsForm'];
    const formData = new FormData();
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    formData.append(input.name, input.value);
                }
            });
        }
    });
    
    // Add expense categories
    formData.append('expense_categories', expenseCategories.join(','));
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');
    
    // Debug: log form data to console
    console.log('Saving settings:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    const btn = document.querySelector('.btn-primary');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('Saving...') ?>';
    btn.disabled = true;
    
    fetch('?ajax=1&action=update_settings', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('settingsMessage');
        msg.style.display = 'block';
        if (data.success) {
            msg.innerHTML = `<div class="alert alert-success">✅ ${data.message}</div>`;
        } else {
            msg.innerHTML = `<div class="alert alert-danger">❌ Error: ${data.message}</div>`;
        }
        setTimeout(() => { msg.style.display = 'none'; }, 5000);
    })
    .catch(err => {
        alert('<?= __('Network error: ') ?>' + err);
    })
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <?= __('Save All Settings') ?>';
        btn.disabled = false;
    });
}

// Load expense categories on page load
document.addEventListener('DOMContentLoaded', loadExpenseCategories);

// ============================================
// LOAD INSTALLED PRINTERS VIA AJAX
// ============================================
function loadPrinters() {
    const select = document.getElementById('printer_name');
    const currentValue = select.value;

    fetch('?ajax=1&action=get_printers')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                // Clear existing options (keep the first placeholder)
                select.innerHTML = '<option value=""><?= __('select_a_printer') ?></option>';
                data.data.forEach(printer => {
                    const option = document.createElement('option');
                    option.value = printer;
                    option.textContent = printer;
                    if (printer === currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value=""><?= __('no_printers_found') ?></option>';
            }
        })
        .catch(() => {
            select.innerHTML = '<option value=""><?= __('error_loading_printers') ?></option>';
        });
}

// Load printers when the page is ready
document.addEventListener('DOMContentLoaded', function() {
    loadPrinters();
});

document.addEventListener('DOMContentLoaded', function() {
    loadPrinters();
});

</script>

<style>
#printerNetworkGroup {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}
#printerNetworkGroup .form-group {
    flex: 1;
    min-width: 120px;
}
</style>