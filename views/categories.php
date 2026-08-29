<?php
$canManage = hasPermission('manage_categories');
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('categories') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openCategoryModal()">
        <i class="fas fa-plus"></i> <?= __('add_category') ?>
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
                    <input type="text" id="searchCategory" class="form-control" placeholder="<?= __('search_categories') ?>" 
                        style="padding-left: 40px;"
                        oninput="toggleClearButton(this)" 
                        onkeyup="loadCategories(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <button class="btn btn-sm btn-outline" onclick="loadCategories()"><i class="fas fa-sync"></i> <?= __('refresh') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table sortable-table" id="categoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('name') ?></th>
                        <th><?= __('slug') ?></th>
                        <th><?= __('products') ?></th>
                        <th><?= __('created_at') ?></th>
                        <th style="text-align: right;" data-no-sort><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    <tr><td colspan="6" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL (Add/Edit) ===== -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="categoryModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_category') ?></h5>
            <button type="button" class="modal-close" onclick="closeCategoryModal()">&times;</button>
        </div>

        <form id="categoryForm" onsubmit="submitCategoryForm(event)">
            <input type="hidden" id="category_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
                <label><i class="fas fa-tag"></i> <?= __('category_name') ?> *</label>
                <input type="text" id="category_name" name="name" class="form-control" placeholder="<?= __('e.g. Electronics') ?>" required>
                <small class="text-muted"><?= __('Slug will be auto-generated from the name.') ?></small>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_category') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeCategoryModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let categoryFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

// Track changes
<?php if ($canManage): ?>
document.querySelectorAll('#categoryForm input').forEach(el => {
    el.addEventListener('input', () => { categoryFormDirty = true; });
});
<?php endif; ?>

// Load Categories
function loadCategories(search = '') {
    fetch(`?ajax=1&action=get_categories&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderCategoryTable(data.data);
            } else {
                document.getElementById('categoryTableBody').innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted"><?= __('Failed to load categories.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('categoryTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
}

// Render Table
function renderCategoryTable(categories) {
    const tbody = document.getElementById('categoryTableBody');
    if (!categories || categories.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }

    let html = '';
    categories.forEach((c, index) => {
        const productCount = c.products_count ?? 0;
        const date = c.created_at ? new Date(c.created_at).toLocaleDateString() : '-';
        
        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editCategory(${c.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${c.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(c.name)}</strong></td>
                <td><code>${escapeHtml(c.slug)}</code></td>
                <td><span class="badge badge-primary">${productCount}</span></td>
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
// Modal Controls
function openCategoryModal() {
    document.getElementById('categoryModalTitle').textContent = '<?= __('add_category') ?>';
    document.getElementById('categoryForm').reset();
    document.getElementById('category_id').value = '';
    categoryFormDirty = false;
    document.getElementById('categoryModal').classList.add('show');
}

function closeCategoryModal() {
    if (categoryFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('categoryModal').classList.remove('show');
    categoryFormDirty = false;
}

// Edit Category
function editCategory(id) {
    fetch(`?ajax=1&action=get_category&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const c = data.data;
                document.getElementById('categoryModalTitle').textContent = '<?= __('edit_category') ?>';
                document.getElementById('category_id').value = c.id;
                document.getElementById('category_name').value = c.name;
                categoryFormDirty = false;
                document.getElementById('categoryModal').classList.add('show');
            } else {
                alert('<?= __('Category not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading category.') ?>'));
}

// Submit Form
function submitCategoryForm(e) {
    e.preventDefault();
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);
    const id = document.getElementById('category_id').value;
    const action = id ? 'update_category' : 'create_category';
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            categoryFormDirty = false;
            closeCategoryModal();
            loadCategories(document.getElementById('searchCategory').value);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

// Delete Category
function deleteCategory(id) {
    if (!confirm('<?= __('confirm_delete_category') ?>')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);

    fetch(`?ajax=1&action=delete_category`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadCategories(document.getElementById('searchCategory').value);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

<?php endif; ?>

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});
</script>