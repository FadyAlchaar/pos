<?php
$canManage = hasPermission('manage_users');
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('user_management') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openUserModal()">
        <i class="fas fa-plus"></i> <?= __('add_user') ?>
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
                    <input type="text" id="searchUser" class="form-control" placeholder="<?= __('Search users...') ?>" 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadUsers(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadUsers()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('name') ?></th>
                        <th><?= __('username') ?></th>
                        <th><?= __('role') ?></th>
                        <th><?= __('status') ?></th>
                        <th><?= __('created_at') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL (Add/Edit) ===== -->
<div class="modal-overlay" id="userModal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="userModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_user') ?></h5>
            <button type="button" class="modal-close" onclick="closeUserModal()">&times;</button>
        </div>

        <form id="userForm" onsubmit="submitUserForm(event)">
            <input type="hidden" id="user_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
                <label><i class="fas fa-user"></i> <?= __('full_name') ?> *</label>
                <input type="text" id="user_name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> <?= __('username') ?> *</label>
                <input type="text" id="user_username" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> <?= __('email') ?> (<?= __('optional') ?>)</label>
                <input type="email" id="user_email" name="email" class="form-control">
            </div>

            <div class="form-group" id="passwordGroup">
                <label><i class="fas fa-lock"></i> <?= __('password') ?> *</label>
                <input type="password" id="user_password" name="password" class="form-control" autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> <?= __('role') ?> *</label>
                <select id="user_role" name="role" class="form-control" required>
                    <option value="admin"><?= __('admin') ?></option>
                    <option value="manager"><?= __('manager') ?></option>
                    <option value="cashier"><?= __('cashier') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-power-off"></i> <?= __('status') ?></label>
                <select id="user_status" name="is_active" class="form-control">
                    <option value="1"><?= __('active') ?></option>
                    <option value="0"><?= __('inactive') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-server"></i> <?= __('device') ?></label>
                <select id="user_device" name="device_id" class="form-control">
                    <option value=""><?= __('none') ?></option>
                    <?php foreach (getDevices() as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['device_name']) ?> (<?= htmlspecialchars($d['device_code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_user') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeUserModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let userFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

<?php if ($canManage): ?>
document.querySelectorAll('#userForm input, #userForm select').forEach(el => {
    el.addEventListener('input', () => { userFormDirty = true; });
});
<?php endif; ?>

function loadUsers(search = '') {
    fetch(`?ajax=1&action=get_users&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderUserTable(data.data);
            } else {
                document.getElementById('usersTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted"><?= __('Failed to load users.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('usersTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

function renderUserTable(users) {
    const tbody = document.getElementById('usersTableBody');
    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    users.forEach((u, index) => {
        const statusBadge = u.is_active ?
            `<span class="badge badge-success"><?= __('active') ?></span>` :
            `<span class="badge badge-secondary"><?= __('inactive') ?></span>`;

        const roleBadge = `<span class="badge badge-primary">${escapeHtml(u.role)}</span>`;
        const date = u.created_at ? new Date(u.created_at).toLocaleDateString() : '-';
        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editUser(${u.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteUser(${u.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(u.name || u.username)}</strong></td>
                <td>${escapeHtml(u.username)}</td>
                <td>${roleBadge}</td>
                <td>${statusBadge}</td>
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
function openUserModal() {
    document.getElementById('userModalTitle').textContent = '<?= __('add_user') ?>';
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('user_status').value = '1';
    document.getElementById('user_password').required = true;
    document.getElementById('passwordGroup').querySelector('label').textContent = '<?= __('password') ?> *';
    userFormDirty = false;
    document.getElementById('userModal').classList.add('show');
}

function closeUserModal() {
    if (userFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('userModal').classList.remove('show');
    userFormDirty = false;
}

function editUser(id) {
    fetch(`?ajax=1&action=get_user&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const u = data.data;
                document.getElementById('user_device').value = u.device_id || '';
                document.getElementById('userModalTitle').textContent = '<?= __('edit_user') ?>';
                document.getElementById('user_id').value = u.id;
                document.getElementById('user_name').value = u.name;
                document.getElementById('user_username').value = u.username;
                document.getElementById('user_email').value = u.email || '';
                document.getElementById('user_role').value = u.role;
                document.getElementById('user_status').value = u.is_active;
                document.getElementById('user_password').value = '';
                document.getElementById('user_password').required = false;
                document.getElementById('passwordGroup').querySelector('label').textContent = '<?= __('password') ?> (<?= __('leave blank to keep current') ?>)';
                userFormDirty = false;
                document.getElementById('userModal').classList.add('show');
            } else {
                alert('<?= __('User not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading user.') ?>'));
}

function submitUserForm(e) {
    e.preventDefault();
    const form = document.getElementById('userForm');
    const formData = new FormData(form);
    const id = document.getElementById('user_id').value;
    const action = id ? 'update_user' : 'create_user';
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            userFormDirty = false;
            closeUserModal();
            loadUsers(document.getElementById('searchUser').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteUser(id) {
    if (!confirm('<?= __('confirm_delete_user') ?>')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=delete_user`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadUsers(document.getElementById('searchUser').value);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) closeUserModal();
});
<?php endif; ?>

document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
});
</script>