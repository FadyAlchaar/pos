<?php
$canManage = hasPermission('manage_customers');
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('customers') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openCustomerModal()">
        <i class="fas fa-plus"></i> <?= __('add_customer') ?>
    </button>
    <?php endif; ?>
</div>

<div class="card fade-in">
    <div class="card-body">
        <!-- Search & Refresh -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div style="position: relative; flex: 1; max-width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <div class="input-clear-wrapper">
                    <input type="text" id="searchCustomer" class="form-control" placeholder="<?= __('Search customers...') ?>" 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadCustomers(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadCustomers()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="customerTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('name') ?></th>
                        <th><?= __('phone') ?></th>
                        <th><?= __('email') ?></th>
                        <th><?= __('address') ?></th>
                        <th><?= __('created_at') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL (Add/Edit) ===== -->
<div class="modal-overlay" id="customerModal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="customerModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_customer') ?></h5>
            <button type="button" class="modal-close" onclick="closeCustomerModal()">&times;</button>
        </div>

        <form id="customerForm" onsubmit="submitCustomerForm(event)">
            <input type="hidden" id="customer_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> <?= __('customer_name') ?> *</label>
                        <input type="text" id="customer_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> <?= __('customer_phone') ?></label>
                        <input type="text" id="customer_phone" name="phone" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> <?= __('customer_email') ?></label>
                        <input type="email" id="customer_email" name="email" class="form-control">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> <?= __('customer_address') ?></label>
                        <input type="text" id="customer_address" name="address" class="form-control">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> <?= __('customer_notes') ?></label>
                        <textarea id="customer_notes" name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_customer') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeCustomerModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let customerFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

<?php if ($canManage): ?>
document.querySelectorAll('#customerForm input, #customerForm textarea').forEach(el => {
    el.addEventListener('input', () => { customerFormDirty = true; });
});
<?php endif; ?>

function loadCustomers(search = '') {
    fetch(`?ajax=1&action=get_customers&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderCustomerTable(data.data);
            } else {
                document.getElementById('customerTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted"><?= __('Failed to load customers.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('customerTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderCustomerTable(customers) {
    const tbody = document.getElementById('customerTableBody');
    if (!customers || customers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    customers.forEach((c, index) => {
        const date = c.created_at ? new Date(c.created_at).toLocaleDateString() : '-';
        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editCustomer(${c.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteCustomer(${c.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(c.name)}</strong></td>
                <td>${escapeHtml(c.phone) || '-'}</td>
                <td>${escapeHtml(c.email) || '-'}</td>
                <td>${escapeHtml(c.address) || '-'}</td>
                <td>${date}</td>
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
function openCustomerModal() {
    document.getElementById('customerModalTitle').textContent = '<?= __('add_customer') ?>';
    document.getElementById('customerForm').reset();
    document.getElementById('customer_id').value = '';
    customerFormDirty = false;
    document.getElementById('customerModal').classList.add('show');
}

function closeCustomerModal() {
    if (customerFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('customerModal').classList.remove('show');
    customerFormDirty = false;
}

function editCustomer(id) {
    fetch(`?ajax=1&action=get_customer&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const c = data.data;
                document.getElementById('customerModalTitle').textContent = '<?= __('edit_customer') ?>';
                document.getElementById('customer_id').value = c.id;
                document.getElementById('customer_name').value = c.name;
                document.getElementById('customer_phone').value = c.phone || '';
                document.getElementById('customer_email').value = c.email || '';
                document.getElementById('customer_address').value = c.address || '';
                document.getElementById('customer_notes').value = c.notes || '';
                customerFormDirty = false;
                document.getElementById('customerModal').classList.add('show');
            } else {
                alert('<?= __('Customer not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading customer.') ?>'));
}

function submitCustomerForm(e) {
    e.preventDefault();
    const form = document.getElementById('customerForm');
    const formData = new FormData(form);
    const id = document.getElementById('customer_id').value;
    const action = id ? 'update_customer' : 'create_customer';
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            customerFormDirty = false;
            closeCustomerModal();
            loadCustomers(document.getElementById('searchCustomer').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteCustomer(id) {
    if (!confirm('<?= __('confirm_delete_customer') ?>')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=delete_customer`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadCustomers(document.getElementById('searchCustomer').value);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

document.getElementById('customerModal').addEventListener('click', function(e) {
    if (e.target === this) closeCustomerModal();
});
<?php endif; ?>

document.addEventListener('DOMContentLoaded', function() {
    loadCustomers();
});
</script>