<?php
$canManage = hasPermission('manage_devices');
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('devices_management') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openDeviceModal()">
        <i class="fas fa-plus"></i> <?= __('add_device') ?>
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
                    <input type="text" id="searchDevice" class="form-control" placeholder="<?= __('Search devices...') ?>" 
                        style="padding-left: 40px;"
                        oninput="toggleClearButton(this)" 
                        onkeyup="loadDevices(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadDevices()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="deviceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('device_name') ?></th>
                        <th><?= __('device_code') ?></th>
                        <th><?= __('status') ?></th>
                        <th><?= __('users_assigned') ?></th>
                        <th><?= __('created_at') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="deviceTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL (Add/Edit) ===== -->
<div class="modal-overlay" id="deviceModal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="deviceModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_device') ?></h5>
            <button type="button" class="modal-close" onclick="closeDeviceModal()">&times;</button>
        </div>

        <form id="deviceForm" onsubmit="submitDeviceForm(event)">
            <input type="hidden" id="device_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
                <label><i class="fas fa-desktop"></i> <?= __('device_name') ?> *</label>
                <input type="text" id="device_name" name="device_name" class="form-control" placeholder="<?= __('e.g. Main POS') ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-qrcode"></i> <?= __('device_code') ?> *</label>
                <input type="text" id="device_code" name="device_code" class="form-control" placeholder="<?= __('e.g. POS-01') ?>" required>
                <small class="text-muted"><?= __('Unique identifier for the device. Must be unique.') ?></small>
            </div>

            <div class="form-group">
                <label><i class="fas fa-power-off"></i> <?= __('status') ?></label>
                <select id="device_status" name="is_active" class="form-control">
                    <option value="1"><?= __('active') ?></option>
                    <option value="0"><?= __('inactive') ?></option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_device') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeDeviceModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let deviceFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

<?php if ($canManage): ?>
document.querySelectorAll('#deviceForm input, #deviceForm select').forEach(el => {
    el.addEventListener('change', () => { deviceFormDirty = true; });
});
<?php endif; ?>

function loadDevices(search = '') {
    fetch(`?ajax=1&action=get_devices&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderDeviceTable(data.data);
            } else {
                document.getElementById('deviceTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted"><?= __('Failed to load devices.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('deviceTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderDeviceTable(devices) {
    const tbody = document.getElementById('deviceTableBody');
    if (!devices || devices.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    devices.forEach((d, index) => {
        const statusBadge = d.is_active ?
            `<span class="badge badge-success"><?= __('active') ?></span>` :
            `<span class="badge badge-secondary"><?= __('inactive') ?></span>`;

        const date = d.created_at ? new Date(d.created_at).toLocaleDateString() : '-';
        const userCount = d.user_count || 0;

        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editDevice(${d.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteDevice(${d.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(d.device_name)}</strong></td>
                <td><code>${escapeHtml(d.device_code)}</code></td>
                <td>${statusBadge}</td>
                <td>${userCount}</td>
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
function openDeviceModal() {
    document.getElementById('deviceModalTitle').textContent = '<?= __('add_device') ?>';
    document.getElementById('deviceForm').reset();
    document.getElementById('device_id').value = '';
    document.getElementById('device_status').value = '1';
    deviceFormDirty = false;
    document.getElementById('deviceModal').classList.add('show');
}

function closeDeviceModal() {
    if (deviceFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('deviceModal').classList.remove('show');
    deviceFormDirty = false;
}

function editDevice(id) {
    fetch(`?ajax=1&action=get_device&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const d = data.data;
                document.getElementById('deviceModalTitle').textContent = '<?= __('edit_device') ?>';
                document.getElementById('device_id').value = d.id;
                document.getElementById('device_name').value = d.device_name;
                document.getElementById('device_code').value = d.device_code;
                document.getElementById('device_status').value = d.is_active;
                deviceFormDirty = false;
                document.getElementById('deviceModal').classList.add('show');
            } else {
                alert('<?= __('Device not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading device.') ?>'));
}

function submitDeviceForm(e) {
    e.preventDefault();
    const form = document.getElementById('deviceForm');
    const formData = new FormData(form);
    const id = document.getElementById('device_id').value;
    const action = id ? 'update_device' : 'create_device';
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            deviceFormDirty = false;
            closeDeviceModal();
            loadDevices(document.getElementById('searchDevice').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteDevice(id) {
    if (!confirm('<?= __('confirm_delete_device') ?>')) return;
    // Check if users are assigned
    fetch(`?ajax=1&action=get_device_users&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.count > 0) {
                if (!confirm(`<?= __('This device has %d users assigned. Deleting it will unassign them. Are you sure?') ?>`.replace('%d', data.count))) return;
            }
            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', csrfToken);
            fetch(`?ajax=1&action=delete_device`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.success) {
                    alert(resData.message);
                    loadDevices(document.getElementById('searchDevice').value);
                } else {
                    alert('Error: ' + resData.message);
                }
            });
        })
        .catch(() => alert('<?= __('Network error.') ?>'));
}

<?php endif; ?>

document.addEventListener('DOMContentLoaded', function() {
    loadDevices();
});
</script>