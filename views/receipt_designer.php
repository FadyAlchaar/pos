<?php
// views/receipt_designer.php
// Prevent direct access - must be accessed via route
if (!isset($_GET['route']) || $_GET['route'] !== 'receipt_designer') {
    header('Location: ?route=receipt_designer');
    exit;
}

// Load dependencies
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Functions.php';

$db = Database::getInstance()->getConnection();

$templateId = $_GET['id'] ?? null;
$template = null;
$settings = [];

if ($templateId) {
    $stmt = $db->prepare("SELECT * FROM receipt_templates WHERE id = ?");
    $stmt->execute([$templateId]);
    $template = $stmt->fetch();
    if ($template) {
        $settings = json_decode($template['settings'], true);
    }
}

$templates = $db->query("SELECT * FROM receipt_templates ORDER BY name")->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('receipt_designer') ?></h4>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="saveTemplate()">
            <i class="fas fa-save"></i> <?= __('save_template') ?>
        </button>
        <button class="btn btn-success" onclick="previewReceipt()">
            <i class="fas fa-eye"></i> <?= __('preview') ?>
        </button>
    </div>
</div>

<div class="row">
    <!-- ===== LEFT: Designer Controls ===== -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-paint-brush"></i> <?= __('design_receipt') ?></h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                <form id="templateForm">
                    <input type="hidden" id="template_id" value="<?= $templateId ?? '' ?>">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <!-- Template Name -->
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> <?= __('template_name') ?></label>
                        <input type="text" id="template_name" class="form-control" value="<?= htmlspecialchars($template['name'] ?? '') ?>" placeholder="e.g. Thermal Receipt">
                    </div>

                    <!-- Direction (LTR / RTL) -->
                    <div class="form-group">
                        <label><i class="fas fa-arrows-alt-h"></i> <?= __('direction') ?></label>
                        <select id="direction" class="form-control">
                            <option value="ltr" <?= ($settings['direction'] ?? 'ltr') === 'ltr' ? 'selected' : '' ?>>LTR (English)</option>
                            <option value="rtl" <?= ($settings['direction'] ?? 'ltr') === 'rtl' ? 'selected' : '' ?>>RTL (Arabic)</option>
                        </select>
                    </div>

                    <!-- Paper Width -->
                    <div class="form-group">
                        <label><i class="fas fa-ruler-horizontal"></i> <?= __('paper_width') ?> (<?= __('characters') ?>)</label>
                        <input type="number" id="paper_width" class="form-control" value="<?= $settings['paper_width'] ?? 40 ?>" min="20" max="80">
                        <small class="text-muted">Thermal printers typically use 40 characters per line.</small>
                    </div>

                    <!-- Font Settings -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-font"></i> <?= __('font_settings') ?></h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= __('font_size') ?></label>
                                <input type="number" id="font_size" class="form-control" value="<?= $settings['font_size'] ?? 12 ?>" min="8" max="24">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><?= __('font_weight') ?></label>
                                <select id="font_weight" class="form-control">
                                    <option value="normal" <?= ($settings['font_weight'] ?? 'normal') === 'normal' ? 'selected' : '' ?>>Normal</option>
                                    <option value="bold" <?= ($settings['font_weight'] ?? 'normal') === 'bold' ? 'selected' : '' ?>>Bold</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ===== TOGGLE FIELDS ===== -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-toggle-on"></i> <?= __('show_hide_fields') ?></h6>

                    <?php
                    $fields = [
                        'store_name' => 'Store Name',
                        'store_address' => 'Store Address',
                        'store_phone' => 'Store Phone',
                        'invoice_no' => 'Invoice Number',
                        'date' => 'Date',
                        'cashier' => 'Cashier',
                        'customer' => 'Customer',
                        'items_table' => 'Items Table',
                        'subtotal' => 'Subtotal',
                        'discount' => 'Discount',
                        'tax' => 'Tax',
                        'total' => 'Total',
                        'footer' => 'Footer Message',
                    ];
                    foreach ($fields as $key => $label):
                        $enabled = $settings[$key]['enabled'] ?? true;
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0" style="font-weight: 500;"><?= $label ?></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="field_<?= $key ?>" 
                                   <?= $enabled ? 'checked' : '' ?> onchange="toggleField('<?= $key ?>')">
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Table Border Style -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-table"></i> <?= __('table_settings') ?></h6>
                    <div class="form-group">
                        <label><?= __('border_style') ?></label>
                        <select id="border_style" class="form-control">
                            <option value="box" <?= ($settings['items_table']['border_style'] ?? 'box') === 'box' ? 'selected' : '' ?>>Box (┌─┐)</option>
                            <option value="line" <?= ($settings['items_table']['border_style'] ?? 'box') === 'line' ? 'selected' : '' ?>>Line (───)</option>
                            <option value="none" <?= ($settings['items_table']['border_style'] ?? 'box') === 'none' ? 'selected' : '' ?>>None</option>
                        </select>
                    </div>

                    <!-- Footer -->
                    <hr>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> <?= __('footer_message') ?></label>
                        <textarea id="footer_text" class="form-control" rows="2"><?= htmlspecialchars($settings['footer_text'] ?? 'Thank you for your business!') ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT: Live Preview ===== -->
    <div class="col-md-6">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-eye"></i> <?= __('live_preview') ?></h5>
            </div>
            <div class="card-body" style="background: #f0f0f0; max-height: 600px; overflow-y: auto;">
                <div id="receiptPreview" style="background: white; padding: 20px; max-width: 300px; margin: 0 auto; font-family: 'Courier New', monospace; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div id="previewContent">
                        <p class="text-muted text-center"><?= __('click_preview_to_generate') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTemplateId = '<?= $templateId ?? '' ?>';

function toggleField(key) {
    // Auto-preview when toggling
    previewReceipt();
}

function previewReceipt() {
    const form = document.getElementById('templateForm');
    const formData = new FormData(form);
    formData.append('action', 'preview');
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');

    const previewDiv = document.getElementById('previewContent');
    previewDiv.innerHTML = '<p class="text-muted text-center"><?= __('loading_preview') ?>...</p>';

    fetch('?ajax=1&action=preview_receipt', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('HTTP error ' + res.status);
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            previewDiv.innerHTML = data.html;
        } else {
            previewDiv.innerHTML = '<p class="text-danger"><?= __('preview_error') ?></p>';
        }
    })
    .catch(err => {
        console.error('Preview error:', err);
        previewDiv.innerHTML = '<p class="text-danger"><?= __('preview_error') ?></p>';
    });
}

function saveTemplate() {
    const form = document.getElementById('templateForm');
    const formData = new FormData(form);
    formData.append('action', 'save');
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');

    const btn = document.querySelector('.btn-primary');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('saving') ?>...';
    btn.disabled = true;

    fetch('?ajax=1&action=save_receipt_template', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success && data.id) {
            currentTemplateId = data.id;
            document.getElementById('template_id').value = data.id;
        }
    })
    .catch(err => alert('Error: ' + err.message))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <?= __('save_template') ?>';
        btn.disabled = false;
    });
}

// Auto-preview on any change
document.querySelectorAll('#templateForm input, #templateForm select, #templateForm textarea').forEach(el => {
    el.addEventListener('change', previewReceipt);
    el.addEventListener('input', previewReceipt);
});

// Load preview on page load
document.addEventListener('DOMContentLoaded', function() {
    previewReceipt();
});
</script>