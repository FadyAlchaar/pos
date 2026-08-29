<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('sales_history') ?></h4>
    <button class="btn btn-sm btn-outline" onclick="loadSales()">
        <i class="fas fa-sync"></i> <?= __('refresh') ?>
    </button>
</div>

<div class="card fade-in">
    <div class="card-body">
        <!-- Search & Filters -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div style="position: relative; flex: 1; max-width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <div class="input-clear-wrapper">
                    <input type="text" id="searchSale" class="form-control" placeholder="Search invoice or customer..." 
                        style="padding-left: 40px;" 
                        oninput="toggleClearButton(this)"
                        onkeyup="loadSales(this.value)">
                    <button type="button" class="clear-btn" onclick="clearInput(this)">✕</button>
                </div>
            </div>
            <div>
                <button class="btn btn-sm btn-success" onclick="exportSales()">
                    <i class="fas fa-file-excel"></i> <?= __('export') ?>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table sortable-table" id="salesTable">
                <thead>
                    <tr>
                        <th><?= __('invoice') ?></th>
                        <th><?= __('customer') ?></th>
                        <th><?= __('total') ?></th>
                        <th><?= __('payment_method') ?></th>
                        <th><?= __('date') ?></th>
                        <th style="text-align: right;" data-no-sort><?= __('actions') ?></th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <tr><td colspan="6" class="text-center text-muted">Loading sales...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== MODAL: Invoice Details ===== -->
<div class="modal-overlay" id="invoiceModal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 style="font-weight: 700; margin: 0;"><?= __('invoice_details') ?></h5>
            <button type="button" class="modal-close" onclick="closeInvoiceModal()">&times;</button>
        </div>
        <div id="invoiceContent">
            <p class="text-muted text-center">Loading...</p>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-primary" onclick="printInvoiceFromModal()"><i class="fas fa-print"></i> <?= __('print') ?></button>
            <button class="btn btn-outline" onclick="closeInvoiceModal()"><?= __('close') ?></button>
        </div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';
let currentInvoiceId = null;

// Load Sales
function loadSales(search = '') {
    fetch(`?ajax=1&action=get_sales&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderSalesTable(data.data);
            } else {
                document.getElementById('salesTableBody').innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted">Failed to load sales.</td></tr>`;
            }
        })
        .catch(() => {
            document.getElementById('salesTableBody').innerHTML =
                `<tr><td colspan="6" class="text-center text-muted">Network error.</td></tr>`;
        });
}

// Render Sales Table
function renderSalesTable(sales) {
    const tbody = document.getElementById('salesTableBody');
    if (!sales || sales.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">No sales found.</td></tr>`;
        return;
    }

    let html = '';
    sales.forEach(s => {
        const date = new Date(s.created_at).toLocaleString();
        html += `
            <tr>
                <td><strong>${s.invoice_no}</strong></td>
                <td>${s.customer_name || 'Walk-in'}</td>
                <td><strong>${formatPrice(s.total)}</strong></td>
                <td><span class="badge badge-success">${s.payment_method}</span></td>
                <td>${date}</td>
                <td style="text-align: right;">
                    <button class="btn btn-sm btn-primary" onclick="viewInvoice(${s.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success" onclick="printNormalReceipt(${s.id})">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="goToReturn('${s.invoice_no}')" title="Return Items">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// View Invoice
function viewInvoice(id) {
    fetch(`?ajax=1&action=get_sale&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentInvoiceId = id;
                renderInvoice(data.data);
                document.getElementById('invoiceModal').classList.add('show');
            } else {
                alert('Error loading invoice.');
            }
        });
}

// ============================================
// RENDER INVOICE CONTENT
// ============================================
function renderInvoice(sale) {
    const container = document.getElementById('invoiceContent');
    let itemsHtml = '';
    sale.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>${formatPrice(item.price)}</td>
                <td>${formatPrice(item.total)}</td>
            </tr>
        `;
    });

    const dir = document.documentElement.dir || 'ltr';
    
    container.innerHTML = `
        <div dir="${dir}">
            <div style="border-bottom: 1px solid #e9ecef; padding-bottom: 15px; margin-bottom: 15px;">
                <h4 style="font-weight: 700;">${sale.invoice_no}</h4>
                <div class="d-flex justify-content-between">
                    <div>
                        <p><strong><?= __('date') ?>:</strong> ${new Date(sale.created_at).toLocaleString()}</p>
                        <p><strong><?= __('cashier') ?>:</strong> ${sale.cashier || 'N/A'}</p>
                    </div>
                    <div style="text-align: right;">
                        <p><strong><?= __('customer') ?>:</strong> ${sale.customer_name || 'Walk-in'}</p>
                        <p><strong><?= __('phone') ?>:</strong> ${sale.customer_phone || 'N/A'}</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?= __('product') ?></th>
                            <th><?= __('quantity') ?></th>
                            <th><?= __('price') ?></th>
                            <th><?= __('total') ?></th>
                        </tr>
                    </thead>
                    <tbody>${itemsHtml}</tbody>
                    <tfoot>
                        <tr><th colspan="3" style="text-align: right;"><?= __('subtotal') ?></th><td>${formatPrice(sale.subtotal)}</td></tr>
                        <tr><th colspan="3" style="text-align: right;"><?= __('discount') ?></th><td>${formatPrice(sale.discount)}</td></tr>
                        <tr><th colspan="3" style="text-align: right;"><?= __('tax') ?></th><td>${formatPrice(sale.tax)}</td></tr>
                        <tr><th colspan="3" style="text-align: right; font-size: 18px;"><?= __('total') ?></th><td style="font-size: 18px; font-weight: 700;">${formatPrice(sale.total)}</td></tr>
                    </tfoot>
                </table>
            </div>
            <div style="margin-top: 15px; text-align: center; color: var(--gray); font-size: 13px;">
                <p><?= __('Thank you for your business!') ?></p>
            </div>
        </div>
    `;
}

// Close Invoice Modal
function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.remove('show');
}

// ============================================
// PRINT INVOICE FROM MODAL
// ============================================
function printInvoiceFromModal() {
    const content = document.getElementById('invoiceContent').innerHTML;
    const win = window.open('', '_blank', 'width=800,height=600');
    win.document.write(`
        <html>
            <head>
                <title>Invoice #${currentInvoiceId}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 40px; }
                    .invoice-header { text-align: center; margin-bottom: 30px; }
                    .invoice-header h2 { margin: 0; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #ddd; }
                    th { background: #f4f6f9; }
                    .text-right { text-align: right; }
                    .total-row { font-weight: bold; font-size: 18px; }
                    .footer { text-align: center; margin-top: 30px; color: #888; }
                    .print-header { display: flex; align-items: center; justify-content: center; gap: 10px; }
                </style>
            </head>
            <body>
                <div class="invoice-header">
                    <div class="print-header">
                        <i class="fas fa-store" style="font-size: 28px; color: #6c63ff;"></i>
                        <h2>POS System</h2>
                    </div>
                    <p>Invoice #${currentInvoiceId}</p>
                </div>
                ${content}
                <div class="footer">
                    <p>Thank you for your business!</p>
                </div>
                <script>
                    window.onload = function() { window.print(); }
                <\/script>
            </body>
        </html>
    `);
    win.document.close();
}

// ============================================
// PRINT NORMAL RECEIPT
// ============================================
function printNormalReceipt(id) {
    fetch(`?ajax=1&action=get_sale&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Instead of building text receipt, print PDF
                printReceipt(id, 'normal');
            }
        });
}

function printReceipt(id, method) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('method', method);
    formData.append('csrf_token', csrfToken);

    fetch('?ajax=1&action=print_receipt', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.pdf_base64) {
            // Open PDF in new window
            const win = window.open('', '_blank');
            win.document.write('<html><body style="margin:0;"><embed width="100%" height="100%" src="data:application/pdf;base64,' + data.pdf_base64 + '" type="application/pdf"></body></html>');
            win.document.close();
            win.print();
        } else if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ Error: ' + data.message);
        }
    });
}

// ============================================
// PRINT THERMAL RECEIPT
// ============================================
function printThermalReceipt(id) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('method', 'usb');
    formData.append('csrf_token', csrfToken);
    
    fetch('?ajax=1&action=print_receipt', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.receipt) {
                const win = window.open('', '_blank', 'width=400,height=600');
                win.document.write('<pre style="font-family: monospace; font-size: 13px; padding: 20px;">' + data.receipt + '</pre>');
                win.document.close();
                win.print();
            } else {
                alert('✅ ' + data.message);
            }
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(err => {
        alert('Network error: ' + err);
    });
}

// ============================================
// EXPORT SALES
// ============================================
function exportSales() {
    const search = document.getElementById('searchSale').value || '';
    window.location.href = `?ajax=1&action=export_sales&search=${encodeURIComponent(search)}`;
}

// ============================================
// GO TO RETURN
// ============================================
function goToReturn(invoice) {
    window.location.href = `?route=returns&invoice=${invoice}`;
}

// Load on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSales();
});

</script>