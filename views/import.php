<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);">Import Products from Alameen</h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Instructions:</strong> Upload a CSV file exported from Alameen Accounting software.
            The file must have the columns in the correct order.
        </div>

        <!-- Upload Form -->
        <form id="importForm" enctype="multipart/form-data" onsubmit="importProducts(event)">
            <div class="d-flex gap-3 align-items-end flex-wrap">
                <div style="flex:1; min-width:200px;">
                    <label><i class="fas fa-file-csv"></i> Select CSV File</label>
                    <input type="file" id="csvFile" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                <div style="min-width:150px;">
                    <label><i class="fas fa-format"></i> File Format</label>
                    <select id="csvFormat" class="form-control">
                        <option value="alameen">Alameen (26 columns)</option>
                        <option value="lio" selected>LIO (6 columns)</option>
                    </select>
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Import Products
                    </button>
                </div>
            </div>
        </form>

        <!-- Progress Bar -->
        <div id="importProgress" style="display:none; margin-top: 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span id="importStatus">Processing...</span>
                <span id="importCount">0 / 0</span>
            </div>
            <div style="background:#e9ecef; border-radius:8px; height:20px; overflow:hidden; margin-top:5px;">
                <div id="importBar" style="height:100%; background: linear-gradient(135deg, #6366f1, #8b5cf6); width:0%; transition:width 0.3s;"></div>
            </div>
        </div>

        <!-- Results -->
        <div id="importResults" style="display:none; margin-top:20px;"></div>
    </div>
</div>

<!-- Sample Format -->
<div class="card fade-in mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-table"></i> Expected CSV Format</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">The CSV file should have the following columns in this exact order:</p>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Column Name</th>
                        <th>Description</th>
                        <th>Required</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Number</td><td>Item number</td><td>✅</td></tr>
                    <tr><td>2</td><td>Name</td><td>Item name</td><td>✅</td></tr>
                    <tr><td>3</td><td>Code</td><td>Item code</td><td>❌</td></tr>
                    <tr><td>4</td><td>BarCode</td><td>Primary barcode</td><td>❌</td></tr>
                    <tr><td>5</td><td>Unity</td><td>Main unit name</td><td>❌</td></tr>
                    <tr><td>6</td><td>Spec</td><td>Unit spec factor</td><td>❌</td></tr>
                    <tr><td>7</td><td>Qty</td><td>Initial stock</td><td>❌</td></tr>
                    <tr><td>8</td><td>Whole</td><td>Wholesale price</td><td>❌</td></tr>
                    <tr><td>9</td><td>Half</td><td>Half price</td><td>❌</td></tr>
                    <tr><td>10</td><td>Retail</td><td>Retail price</td><td>❌</td></tr>
                    <tr><td>11</td><td>EndUser</td><td>End user price</td><td>❌</td></tr>
                    <tr><td>12</td><td>Unit2</td><td>Second unit name</td><td>❌</td></tr>
                    <tr><td>13</td><td>Unit2Fact</td><td>Unit2 factor</td><td>❌</td></tr>
                    <tr><td>14</td><td>Unit3</td><td>Third unit name</td><td>❌</td></tr>
                    <tr><td>15</td><td>Unit3Fact</td><td>Unit3 factor</td><td>❌</td></tr>
                    <tr><td>16</td><td>BarCode2</td><td>Unit2 barcode</td><td>❌</td></tr>
                    <tr><td>17</td><td>BarCode3</td><td>Unit3 barcode</td><td>❌</td></tr>
                    <tr><td>18</td><td>Whole2</td><td>Unit2 wholesale price</td><td>❌</td></tr>
                    <tr><td>19</td><td>Half2</td><td>Unit2 half price</td><td>❌</td></tr>
                    <tr><td>20</td><td>Retail2</td><td>Unit2 retail price</td><td>❌</td></tr>
                    <tr><td>21</td><td>EndUser2</td><td>Unit2 end user price</td><td>❌</td></tr>
                    <tr><td>22</td><td>Whole3</td><td>Unit3 wholesale price</td><td>❌</td></tr>
                    <tr><td>23</td><td>Half3</td><td>Unit3 half price</td><td>❌</td></tr>
                    <tr><td>24</td><td>Retail3</td><td>Unit3 retail price</td><td>❌</td></tr>
                    <tr><td>25</td><td>EndUser3</td><td>Unit3 end user price</td><td>❌</td></tr>
                    <tr><td>26</td><td>GUID</td><td>Unique identifier</td><td>❌</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function importProducts(e) {
    e.preventDefault();
    const fileInput = document.getElementById('csvFile');
    const file = fileInput.files[0];
    const format = document.getElementById('csvFormat').value;
    
    if (!file) {
        alert('Please select a CSV file.');
        return;
    }
    
    const formData = new FormData();
    formData.append('csv_file', file);
    formData.append('format', format);
    formData.append('csrf_token', '<?= generateCSRFToken() ?>');
    
    // Show progress
    document.getElementById('importProgress').style.display = 'block';
    document.getElementById('importStatus').textContent = 'Uploading file...';
    document.getElementById('importBar').style.width = '20%';
    
    fetch('?ajax=1&action=import_products', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('importBar').style.width = '100%';
        document.getElementById('importStatus').textContent = 'Complete!';
        
        const results = document.getElementById('importResults');
        results.style.display = 'block';
        
        if (data.success) {
            let errorHtml = '';
            if (data.error_messages && data.error_messages.length > 0) {
                const sampleErrors = data.error_messages.slice(0, 10);
                errorHtml = `
                    <div style="margin-top: 10px; max-height: 200px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 12px;">
                        <strong>Sample errors (first 10):</strong>
                        <ul>
                            ${sampleErrors.map(msg => `<li>${msg}</li>`).join('')}
                        </ul>
                        ${data.error_messages.length > 10 ? `<li>... and ${data.error_messages.length - 10} more errors</li>` : ''}
                    </div>
                `;
            }
            
            results.innerHTML = `
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle"></i> Import Complete!</h5>
                    <p><strong>${data.inserted}</strong> products imported successfully.</p>
                    <p>${data.skipped > 0 ? `<strong>${data.skipped}</strong> products skipped (duplicates).` : ''}</p>
                    <p>${data.errors > 0 ? `<strong>${data.errors}</strong> errors encountered.` : ''}</p>
                    ${errorHtml}
                    <a href="?route=products" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-boxes"></i> View Products
                    </a>
                </div>
            `;
        } else {
            results.innerHTML = `
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-circle"></i> Import Failed</h5>
                    <p>${data.message}</p>
                </div>
            `;
        }
    })
    .catch(err => {
        document.getElementById('importBar').style.width = '100%';
        document.getElementById('importStatus').textContent = 'Error';
        document.getElementById('importResults').style.display = 'block';
        document.getElementById('importResults').innerHTML = `
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-circle"></i> Error</h5>
                <p>${err.message}</p>
            </div>
        `;
    });
}
</script>