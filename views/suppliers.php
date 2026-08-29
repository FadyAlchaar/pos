<?php
$canManage = hasPermission('manage_inventory');
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('suppliers') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openSupplierModal()">
        <i class="fas fa-plus"></i> <?= __('add_supplier') ?>
    </button>
    <?php endif; ?>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div style="position: relative; flex: 1; max-width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <div class="input-clear-wrapper">
                    <input type="text" id="searchSupplier" class="form-control" placeholder="<?= __('Search suppliers...') ?>" 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadSuppliers(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadSuppliers()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <div class="table-responsive">
            <table class="table sortable-table" id="supplierTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('supplier_name') ?></th>
                        <th><?= __('contact_person') ?></th>
                        <th><?= __('phone') ?></th>
                        <th><?= __('email') ?></th>
                        <th style="text-align: right;" data-no-sort><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="supplierTableBody">
                    <tr><td colspan="6" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<div class="modal-overlay" id="supplierModal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="supplierModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_supplier') ?></h5>
            <button type="button" class="modal-close" onclick="closeSupplierModal()">&times;</button>
        </div>

        <form id="supplierForm" onsubmit="submitSupplierForm(event)">
            <input type="hidden" id="supplier_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
                <label><i class="fas fa-building"></i> <?= __('supplier_name') ?> *</label>
                <input type="text" id="supplier_name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user"></i> <?= __('contact_person') ?></label>
                <input type="text" id="supplier_contact" name="contact_person" class="form-control">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> <?= __('phone') ?></label>
                        <input type="text" id="supplier_phone" name="phone" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> <?= __('email') ?></label>
                        <input type="email" id="supplier_email" name="email" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> <?= __('address') ?></label>
                <textarea id="supplier_address" name="address" class="form-control" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
                <textarea id="supplier_notes" name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_supplier') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeSupplierModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let supplierFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

<?php if ($canManage): ?>
document.querySelectorAll('#supplierForm input, #supplierForm textarea').forEach(el => {
    el.addEventListener('input', () => { supplierFormDirty = true; });
});
<?php endif; ?>

function loadSuppliers(search = '') {
    fetch(`?ajax=1&action=get_suppliers&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderSupplierTable(data.data);
            } else {
                document.getElementById('supplierTableBody').innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted"><?= __('Failed to load suppliers.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('supplierTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderSupplierTable(suppliers) {
    const tbody = document.getElementById('supplierTableBody');
    if (!suppliers || suppliers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    suppliers.forEach((s, index) => {
        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editSupplier(${s.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteSupplier(${s.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(s.name)}</strong></td>
                <td>${escapeHtml(s.contact_person) || '-'}</td>
                <td>${escapeHtml(s.phone) || '-'}</td>
                <td>${escapeHtml(s.email) || '-'}</td>
                <td style="text-align: right;">
                    ${actionsHtml}
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

<?php if ($canManage): ?>
function openSupplierModal() {
    document.getElementById('supplierModalTitle').textContent = '<?= __('add_supplier') ?>';
    document.getElementById('supplierForm').reset();
    document.getElementById('supplier_id').value = '';
    supplierFormDirty = false;
    document.getElementById('supplierModal').classList.add('show');
}

function closeSupplierModal() {
    if (supplierFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('supplierModal').classList.remove('show');
    supplierFormDirty = false;
}

function editSupplier(id) {
    fetch(`?ajax=1&action=get_supplier&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const s = data.data;
                document.getElementById('supplierModalTitle').textContent = '<?= __('edit_supplier') ?>';
                document.getElementById('supplier_id').value = s.id;
                document.getElementById('supplier_name').value = s.name;
                document.getElementById('supplier_contact').value = s.contact_person || '';
                document.getElementById('supplier_phone').value = s.phone || '';
                document.getElementById('supplier_email').value = s.email || '';
                document.getElementById('supplier_address').value = s.address || '';
                document.getElementById('supplier_notes').value = s.notes || '';
                supplierFormDirty = false;
                document.getElementById('supplierModal').classList.add('show');
            } else {
                alert('<?= __('Supplier not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading supplier.') ?>'));
}

function submitSupplierForm(e) {
    e.preventDefault();
    const form = document.getElementById('supplierForm');
    const formData = new FormData(form);
    const id = document.getElementById('supplier_id').value;
    const action = id ? 'update_supplier' : 'create_supplier';
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            supplierFormDirty = false;
            closeSupplierModal();
            loadSuppliers(document.getElementById('searchSupplier').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteSupplier(id) {
    if (!confirm('<?= __('Are you sure you want to delete this supplier?') ?>')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=delete_supplier`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadSuppliers(document.getElementById('searchSupplier').value);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

<?php endif; ?>

document.addEventListener('DOMContentLoaded', function() {
    loadSuppliers();
});
</script>