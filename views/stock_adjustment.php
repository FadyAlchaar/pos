<?php
$canManage = hasPermission('manage_inventory');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark); margin: 0;"><?= __('stock_adjustment') ?></h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <?= __('Increase or decrease stock for a product. Search for the product, enter quantity, and choose adjustment type.') ?>
        </div>

        <form id="stockAdjustForm" onsubmit="submitStockAdjust(event)">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-box"></i> <?= __('product') ?> *</label>
                        <div style="position: relative;">
                            <input type="text" id="adjust_product_search" class="form-control" 
                                   placeholder="<?= __('search_product_by_name_barcode') ?>" 
                                   onkeyup="searchAdjustProduct(this.value)" autocomplete="off">
                            <input type="hidden" id="adjust_product_id" name="product_id" value="">
                            <div id="adjust_product_results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; max-height: 250px; overflow-y: auto; display: none; z-index: 1000;"></div>
                        </div>
                        <small id="adjust_product_info" class="text-muted"><?= __('Select a product to see current stock and price.') ?></small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-sort-amount-up"></i> <?= __('quantity') ?> *</label>
                        <input type="number" id="adjust_quantity" name="quantity" class="form-control" required min="1" value="1">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-arrow-up"></i> <?= __('type') ?></label>
                        <select id="adjust_type" name="type" class="form-control">
                            <option value="add"><?= __('add_stock') ?></option>
                            <option value="subtract"><?= __('subtract_stock') ?></option>
                            <option value="set"><?= __('set_exact') ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> <?= __('current_stock') ?></label>
                        <input type="text" id="adjust_current_stock" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> <?= __('price') ?></label>
                        <input type="text" id="adjust_product_price" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-sticky-note"></i> <?= __('reason') ?></label>
                <input type="text" id="adjust_reason" name="reason" class="form-control" placeholder="<?= __('e.g. New purchase, damaged items, etc.') ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?= __('apply_adjustment') ?>
            </button>
        </form>

        <div id="adjustResult" style="display:none; margin-top:20px;"></div>
    </div>
</div>

<script>
const csrfToken = '<?= generateCSRFToken() ?>';

function searchAdjustProduct(search) {
    const resultsDiv = document.getElementById('adjust_product_results');
    
    if (search.length < 2) {
        resultsDiv.style.display = 'none';
        document.getElementById('adjust_product_info').textContent = '<?= __('Select a product to see current stock and price.') ?>';
        document.getElementById('adjust_current_stock').value = '';
        document.getElementById('adjust_product_price').value = '';
        return;
    }
    
    fetch(`?ajax=1&action=search_products_for_transfer&search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(p => {
                    html += `<div class="product-result-item" data-id="${p.id}" data-price="${p.price}" data-stock="${p.stock}" 
                                   onclick="selectAdjustProduct(this)" 
                                   style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0;">
                        <strong>${escapeHtml(p.name)}</strong> 
                        <span class="text-muted">${p.barcode || ''}</span>
                        <span style="float:right;">Stock: ${p.stock} | $${parseFloat(p.price).toFixed(2)}</span>
                    </div>`;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.innerHTML = `<div style="padding: 8px 12px; color: #999;"><?= __('no_products_found') ?></div>`;
                resultsDiv.style.display = 'block';
            }
        });
}

function selectAdjustProduct(element) {
    const id = element.dataset.id;
    const price = element.dataset.price;
    const stock = element.dataset.stock;
    const name = element.innerText.trim();
    
    document.getElementById('adjust_product_search').value = name;
    document.getElementById('adjust_product_id').value = id;
    document.getElementById('adjust_current_stock').value = stock;
    document.getElementById('adjust_product_price').value = '$' + parseFloat(price).toFixed(2);
    document.getElementById('adjust_product_info').textContent = '<?= __('Selected:') ?> ' + name;
    document.getElementById('adjust_product_results').style.display = 'none';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function submitStockAdjust(e) {
    e.preventDefault();
    const form = document.getElementById('stockAdjustForm');
    const productId = document.getElementById('adjust_product_id').value;
    const quantity = parseInt(document.getElementById('adjust_quantity').value);
    const type = document.getElementById('adjust_type').value;
    const reason = document.getElementById('adjust_reason').value || '';

    if (!productId || quantity <= 0) {
        alert('<?= __('Please select a product and enter a valid quantity.') ?>');
        return;
    }

    const data = {
        product_id: parseInt(productId),
        quantity: quantity,
        type: type,
        reason: reason,
        csrf_token: csrfToken
    };

    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= __('processing') ?>...';
    btn.disabled = true;

    fetch('?ajax=1&action=stock_adjustment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        const resultDiv = document.getElementById('adjustResult');
        resultDiv.style.display = 'block';
        if (result.success) {
            resultDiv.innerHTML = `<div class="alert alert-success">✅ ${result.message}</div>`;
            // Update current stock display
            document.getElementById('adjust_current_stock').value = result.new_stock;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger">❌ ${result.message}</div>`;
        }
        setTimeout(() => { resultDiv.style.display = 'none'; }, 5000);
    })
    .catch(err => alert('<?= __('Network error') ?>'))
    .finally(() => {
        btn.innerHTML = '<i class="fas fa-save"></i> <?= __('apply_adjustment') ?>';
        btn.disabled = false;
    });
}
</script>