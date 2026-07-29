<?php
$canManage = hasPermission('manage_expenses');
$canView = hasPermission('view_expenses');
// If user can't view, redirect or show error (but route already handles)
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('expenses') ?></h4>
    <?php if ($canManage): ?>
    <button class="btn btn-primary btn-sm" onclick="openExpenseModal()">
        <i class="fas fa-plus"></i> <?= __('add_expense') ?>
    </button>
    <?php endif; ?>
    <button class="btn btn-sm btn-outline" onclick="loadExpenses()">
        <i class="fas fa-sync"></i> <?= __('refresh') ?>
    </button>
</div>

<!-- Summary Cards -->
<div class="dashboard-stats" id="expenseSummary">
    <div class="stat-card primary">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h6>Total Expenses</h6>
            <h2 id="totalExpenses">$0.00</h2>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <h6>This Month</h6>
            <h2 id="monthExpenses">$0.00</h2>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
        <div class="stat-info">
            <h6>This Week</h6>
            <h2 id="weekExpenses">$0.00</h2>
        </div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <h6>Today</h6>
            <h2 id="todayExpenses">$0.00</h2>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<div class="card fade-in mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Expenses by Category</h5>
    </div>
    <div class="card-body">
        <div id="categoryBreakdown" style="display: flex; flex-wrap: wrap; gap: 10px;">
            <p class="text-muted">Loading categories...</p>
        </div>
    </div>
</div>

<!-- Expenses Table -->
<div class="card fade-in">
    <div class="card-body">
        <!-- Search & Filters -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div style="position: relative; flex: 1; min-width: 200px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <div class="input-clear-wrapper">
                    <input type="text" id="searchExpense" class="form-control" placeholder="<?= __('Search expenses...') ?>" 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadExpenses()">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <input type="date" id="startDate" class="form-control" style="width: 150px;" onchange="loadExpenses()">
            <input type="date" id="endDate" class="form-control" style="width: 150px;" onchange="loadExpenses()">
            <button class="btn btn-sm btn-outline" onclick="clearFilters()"><i class="fas fa-times"></i> <?= __('clear') ?></button>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table" id="expenseTable">
                <thead>
                    <tr>
                        <th><?= __('date') ?></th>
                        <th><?= __('category') ?></th>
                        <th><?= __('description') ?></th>
                        <th><?= __('amount') ?></th>
                        <th><?= __('payment_method') ?></th>
                        <th><?= __('by') ?></th>
                        <th style="text-align: right;"><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="expenseTableBody">
                    <tr><td colspan="7" class="text-center text-muted"><?= __('loading') ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($canManage): ?>
<!-- ===== MODAL (Add/Edit) ===== -->
<div class="modal-overlay" id="expenseModal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 id="expenseModalTitle" style="font-weight: 700; margin: 0;"><?= __('add_expense') ?></h5>
            <button type="button" class="modal-close" onclick="closeExpenseModal()">&times;</button>
        </div>

        <form id="expenseForm" onsubmit="submitExpenseForm(event)">
            <input type="hidden" id="expense_id" name="id" value="">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> <?= __('date') ?> *</label>
                        <input type="date" id="expense_date" name="expense_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> <?= __('category') ?> *</label>
                        <select id="expense_category" name="category" class="form-control" required>
                            <?php foreach (getExpenseCategories() as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> <?= __('description') ?> *</label>
                        <input type="text" id="expense_description" name="description" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> <?= __('amount') ?> *</label>
                        <input type="number" step="0.01" id="expense_amount" name="amount" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> <?= __('payment_method') ?></label>
                        <select id="expense_payment" name="payment_method" class="form-control">
                            <option value="cash"><?= __('cash') ?></option>
                            <option value="card"><?= __('card') ?></option>
                            <option value="bank"><?= __('bank_transfer') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> <?= __('notes') ?></label>
                        <textarea id="expense_notes" name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> <?= __('save_expense') ?>
                </button>
                <button type="button" class="btn btn-outline" onclick="closeExpenseModal()"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let expenseFormDirty = false;
const canManage = <?= $canManage ? 'true' : 'false' ?>;

// Track changes in form fields
<?php if ($canManage): ?>
document.querySelectorAll('#expenseForm input, #expenseForm select, #expenseForm textarea').forEach(el => {
    el.addEventListener('change', () => { expenseFormDirty = true; });
});
<?php endif; ?>

// Set today's date
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('expense_date').value = today;
    loadExpenses();
});

// ============================================
// LOAD EXPENSES
// ============================================
function loadExpenses() {
    const search = document.getElementById('searchExpense').value || '';
    const startDate = document.getElementById('startDate').value || '';
    const endDate = document.getElementById('endDate').value || '';
    
    let url = `?ajax=1&action=get_expenses&search=${encodeURIComponent(search)}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderExpenseTable(data.data);
            } else {
                document.getElementById('expenseTableBody').innerHTML =
                    `<tr><td colspan="7" class="text-center text-muted"><?= __('Failed to load expenses.') ?></td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('expenseTableBody').innerHTML =
                `<tr><td colspan="7" class="text-center text-muted"><?= __('Network error.') ?></td></tr>`;
        });
    
    loadSummary(startDate, endDate);
}

// ============================================
// LOAD SUMMARY
// ============================================
function loadSummary(startDate, endDate) {
    let url = `?ajax=1&action=get_expense_summary`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalExpenses').textContent = formatPrice(data.data.total || 0);
                if (data.data.by_category && data.data.by_category.length > 0) {
                    renderCategoryBreakdown(data.data.by_category);
                } else {
                    document.getElementById('categoryBreakdown').innerHTML = '<p class="text-muted"><?= __('No expenses recorded yet.') ?></p>';
                }
            }
        });
    
    // Today
    const today = new Date().toISOString().split('T')[0];
    fetch(`?ajax=1&action=get_expense_summary&start_date=${today}&end_date=${today}`)
        .then(res => res.json())
        .then(d => {
            if (d.success) document.getElementById('todayExpenses').textContent = formatPrice(parseFloat(d.data.total || 0));
        });
    
    // This Week
    const weekStart = new Date();
    weekStart.setDate(weekStart.getDate() - weekStart.getDay());
    const weekStartStr = weekStart.toISOString().split('T')[0];
    const weekEnd = new Date();
    weekEnd.setDate(weekEnd.getDate() + (6 - weekEnd.getDay()));
    const weekEndStr = weekEnd.toISOString().split('T')[0];
    fetch(`?ajax=1&action=get_expense_summary&start_date=${weekStartStr}&end_date=${weekEndStr}`)
        .then(res => res.json())
        .then(d => {
            if (d.success) document.getElementById('weekExpenses').textContent = formatPrice(parseFloat(d.data.total || 0));
        });
    
    // This Month
    const monthStart = new Date();
    monthStart.setDate(1);
    const monthStartStr = monthStart.toISOString().split('T')[0];
    const monthEnd = new Date();
    monthEnd.setMonth(monthEnd.getMonth() + 1);
    monthEnd.setDate(0);
    const monthEndStr = monthEnd.toISOString().split('T')[0];
    fetch(`?ajax=1&action=get_expense_summary&start_date=${monthStartStr}&end_date=${monthEndStr}`)
        .then(res => res.json())
        .then(d => {
            if (d.success) document.getElementById('monthExpenses').textContent = formatPrice(parseFloat(d.data.total || 0));
        });
}

// ============================================
// RENDER CATEGORY BREAKDOWN
// ============================================
function renderCategoryBreakdown(categories) {
    const container = document.getElementById('categoryBreakdown');
    const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#64748b', '#ec4899'];
    let html = '';
    const total = categories.reduce((sum, c) => sum + parseFloat(c.total), 0);
    
    categories.forEach((cat, index) => {
        const pct = total > 0 ? (parseFloat(cat.total) / total * 100) : 0;
        const color = colors[index % colors.length];
        html += `
            <div style="flex: 1; min-width: 160px; background: rgba(255,255,255,0.5); backdrop-filter: blur(10px); padding: 14px 18px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.8); border-left: 4px solid ${color};">
                <div style="font-weight: 600; font-size: 14px;">${escapeHtml(cat.category)}</div>
                <div style="font-size: 18px; font-weight: 700;">${formatPrice(parseFloat(cat.total))}</div>
                <div style="font-size: 12px; color: var(--gray);">${cat.count} <?= __('entries') ?> (${pct.toFixed(1)}%)</div>
                <div style="background: rgba(0,0,0,0.08); border-radius: 6px; margin-top: 6px; height: 6px;">
                    <div style="height: 100%; width: ${pct}%; background: ${color}; border-radius: 6px;"></div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// ============================================
// RENDER EXPENSE TABLE
// ============================================
function renderExpenseTable(expenses) {
    const tbody = document.getElementById('expenseTableBody');
    if (!expenses || expenses.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted"><?= __('no_data') ?></td></tr>`;
        return;
    }
    
    let html = '';
    expenses.forEach(e => {
        const date = e.expense_date ? new Date(e.expense_date).toLocaleDateString() : '-';
        
        // Build actions based on permissions
        let actionsHtml = '';
        if (canManage) {
            actionsHtml = `
                <button class="btn btn-sm btn-primary" onclick="editExpense(${e.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteExpense(${e.id})">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }
        
        html += `
            <tr>
                <td>${date}</td>
                <td><span class="badge badge-primary">${escapeHtml(e.category)}</span></td>
                <td>${escapeHtml(e.description)}</td>
                <td><strong>${formatPrice(parseFloat(e.amount))}</strong></td>
                <td><span class="badge badge-secondary">${escapeHtml(e.payment_method || 'cash')}</span></td>
                <td>${escapeHtml(e.created_by_name || 'System')}</td>
                <td style="text-align: right;">
                    ${actionsHtml}
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// ============================================
// UTILITY
// ============================================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function clearFilters() {
    document.getElementById('searchExpense').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    loadExpenses();
}

// ============================================
// MODAL CONTROLS (Only if user has permission)
// ============================================
<?php if ($canManage): ?>
function openExpenseModal() {
    document.getElementById('expenseModalTitle').textContent = '<?= __('add_expense') ?>';
    document.getElementById('expenseForm').reset();
    document.getElementById('expense_id').value = '';
    document.getElementById('expense_date').value = new Date().toISOString().split('T')[0];
    expenseFormDirty = false;
    document.getElementById('expenseModal').classList.add('show');
}

function closeExpenseModal() {
    if (expenseFormDirty) {
        if (!confirm('<?= __('You have unsaved changes. Are you sure you want to close?') ?>')) return;
    }
    document.getElementById('expenseModal').classList.remove('show');
    expenseFormDirty = false;
}

function editExpense(id) {
    // Frontend permission guard
    if (!canManage) {
        alert('<?= __('You do not have permission to edit expenses.') ?>');
        return;
    }
    
    fetch(`?ajax=1&action=get_expense&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const e = data.data;
                document.getElementById('expenseModalTitle').textContent = '<?= __('edit_expense') ?>';
                document.getElementById('expense_id').value = e.id;
                document.getElementById('expense_date').value = e.expense_date;
                document.getElementById('expense_category').value = e.category;
                document.getElementById('expense_description').value = e.description;
                document.getElementById('expense_amount').value = e.amount;
                document.getElementById('expense_payment').value = e.payment_method || 'cash';
                document.getElementById('expense_notes').value = e.notes || '';
                expenseFormDirty = false;
                document.getElementById('expenseModal').classList.add('show');
            } else {
                alert('<?= __('Expense not found.') ?>');
            }
        })
        .catch(() => alert('<?= __('Error loading expense.') ?>'));
}

function submitExpenseForm(e) {
    e.preventDefault();
    const form = document.getElementById('expenseForm');
    const formData = new FormData(form);
    const id = document.getElementById('expense_id').value;
    const action = id ? 'update_expense' : 'create_expense';
    formData.append('csrf_token', csrfToken);
    
    fetch(`?ajax=1&action=${action}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            expenseFormDirty = false;
            closeExpenseModal();
            loadExpenses();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('<?= __('Network error.') ?>'));
}

function deleteExpense(id) {
    // Frontend permission guard
    if (!canManage) {
        alert('<?= __('You do not have permission to delete expenses.') ?>');
        return;
    }
    
    if (!confirm('<?= __('confirm_delete_expense') ?>')) return;
    
    const formData = new FormData();
    formData.append('id', id);
    formData.append('csrf_token', csrfToken);
    fetch(`?ajax=1&action=delete_expense`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadExpenses();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

// Close modal on outside click
document.getElementById('expenseModal').addEventListener('click', function(e) {
    if (e.target === this) closeExpenseModal();
});
<?php endif; ?>

// ============================================
// LOAD ON PAGE LOAD
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadExpenses();
});
</script>