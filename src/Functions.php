<?php
// src/Functions.php - ALL functions in one structured file

require_once __DIR__ . '/Database.php';

// ============================================
// DEVICE FUNCTIONS (Session & Current Device)
// ============================================
function getCurrentDeviceId() {
    return $_SESSION['device_id'] ?? null;
}

function getCurrentDevice() {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    if (!$deviceId) return null;
    $stmt = $db->prepare("SELECT * FROM devices WHERE id = ?");
    $stmt->execute([$deviceId]);
    return $stmt->fetch();
}

function getDevices() {
    $db = Database::getInstance()->getConnection();
    return $db->query("SELECT * FROM devices ORDER BY device_name")->fetchAll();
}

function switchDevice($deviceId) {
    $_SESSION['device_id'] = $deviceId;
}

function getDeviceFilter($alias = '') {
    $deviceId = getCurrentDeviceId();
    if (!$deviceId) return '';
    return ($alias ? "$alias." : "") . "device_id = $deviceId";
}

// ============================================
// CSRF PROTECTION
// ============================================
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// USER FUNCTIONS
// ============================================
function getUserById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAllUsers($search = '') {
    $db = Database::getInstance()->getConnection();
    if (!empty($search)) {
        $stmt = $db->prepare("SELECT id, name, username, email, role, is_active, created_at FROM users 
                              WHERE username LIKE ? OR email LIKE ? OR name LIKE ? ORDER BY id DESC");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $db->query("SELECT id, name, username, email, role, is_active, created_at FROM users ORDER BY id DESC");
    }
    return $stmt->fetchAll();
}

function createUser($name, $username, $email, $password, $role = 'cashier', $device_id = null, $preferred_language = 'en') {
    $db = Database::getInstance()->getConnection();
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (name, username, email, password, role, device_id, preferred_language) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $username, $email, $hashed, $role, $device_id, $preferred_language]);
}

function updateUser($id, $name, $username, $email, $role, $is_active, $device_id = null, $preferred_language = null) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE users SET name = ?, username = ?, email = ?, role = ?, is_active = ?, device_id = ?, preferred_language = ? WHERE id = ?");
    return $stmt->execute([$name, $username, $email, $role, $is_active, $device_id, $preferred_language, $id]);
}

function deleteUser($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    return $stmt->execute([$id]);
}

// ============================================
// PRODUCT FUNCTIONS
// ============================================
function getAllProducts($search = '', $limit = 50, $offset = 0) {
    $db = Database::getInstance()->getConnection();
    $limit = (int)$limit;
    $offset = (int)$offset;
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "p.device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(p.name LIKE ? OR p.barcode LIKE ? OR p.barcode2 LIKE ? OR p.barcode3 LIKE ? 
                          OR p.alameen_code LIKE ? OR p.alameen_number LIKE ? OR p.coded_code LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    $sql = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY p.id DESC LIMIT $limit OFFSET $offset";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getTotalProducts($search = '') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR barcode LIKE ? OR barcode2 LIKE ? OR barcode3 LIKE ? 
                          OR alameen_code LIKE ? OR alameen_number LIKE ? OR coded_code LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    $sql = "SELECT COUNT(*) as total FROM products";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return (int)$result['total'];
}

function getProductById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createProduct($data) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $categoryId = !empty($data['category_id']) ? $data['category_id'] : null;
    
    $stmt = $db->prepare("INSERT INTO products (
        device_id, name, barcode, description, price, cost, stock, min_stock, category_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $deviceId,
        $data['name'],
        $data['barcode'] ?? null,
        $data['description'] ?? null,
        $data['price'],
        $data['cost'] ?? 0,
        $data['stock'] ?? 0,
        $data['min_stock'] ?? 5,
        $categoryId
    ]);
}

function updateProduct($id, $data) {
    $db = Database::getInstance()->getConnection();
    // Convert empty category_id to NULL
    $categoryId = !empty($data['category_id']) ? $data['category_id'] : null;
    
    $stmt = $db->prepare("UPDATE products SET 
        name = ?, barcode = ?, description = ?, price = ?, cost = ?, 
        stock = ?, min_stock = ?, category_id = ?, is_active = ? 
        WHERE id = ?");
    return $stmt->execute([
        $data['name'],
        $data['barcode'] ?? null,
        $data['description'] ?? null,
        $data['price'],
        $data['cost'] ?? 0,
        $data['stock'] ?? 0,
        $data['min_stock'] ?? 5,
        $categoryId, // <-- FIXED: convert empty to NULL
        $data['is_active'] ?? 1,
        $id
    ]);
}

function deleteProduct($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

function updateStock($productId, $quantity) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
    return $stmt->execute([$quantity, $productId, $quantity]);
}

// ============================================
// CATEGORY FUNCTIONS
// ============================================
function getAllCategories($search = '') {
    $db = Database::getInstance()->getConnection();
    $params = [];
    $conditions = [];

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR slug LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql = "SELECT * FROM categories";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY name";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCategoryById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createCategory($name) {
    $db = Database::getInstance()->getConnection();
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)));
    $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    if ($stmt->execute([$name, $slug])) {
        return $db->lastInsertId();
    }
    return false;
}

function updateCategory($id, $name) {
    $db = Database::getInstance()->getConnection();
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)));
    $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
    return $stmt->execute([$name, $slug, $id]);
}

function deleteCategory($id) {
    $db = Database::getInstance()->getConnection();
    // Check if category has products
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    if ($result['count'] > 0) {
        return ['success' => false, 'message' => 'Cannot delete: Category has products assigned.'];
    }
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    return ['success' => $stmt->execute([$id]), 'message' => 'Category deleted.'];
}

// ============================================
// SALE FUNCTIONS
// ============================================
function createSale($data) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    $deviceId = getCurrentDeviceId();
    
    try {
        $invoiceNo = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $stmt = $db->prepare("INSERT INTO sales (
            device_id, invoice_no, user_id, customer_id, customer_name, customer_phone, 
            subtotal, discount, tax, total, payment_method
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $deviceId,
            $invoiceNo,
            $data['user_id'],
            $data['customer_id'] ?? null,
            $data['customer_name'] ?? null,
            $data['customer_phone'] ?? null,
            $data['subtotal'],
            $data['discount'] ?? 0,
            $data['tax'] ?? 0,
            $data['total'],
            $data['payment_method'] ?? 'cash'
        ]);
        
        $saleId = $db->lastInsertId();
        
        // Insert sale items
        foreach ($data['items'] as $item) {
            $itemDiscount = $item['discount'] ?? 0;
            $itemTotal = ($item['price'] * $item['quantity']) - $itemDiscount;
            $stmt = $db->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price, discount, total) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$saleId, $item['product_id'], $item['quantity'], $item['price'], $itemDiscount, $itemTotal]);
            updateStock($item['product_id'], $item['quantity']);
        }
        
        // ==========================================
        // 🔥 ADD CASH TRANSACTION (CASH IN)
        // ==========================================
        $stmt = $db->prepare("INSERT INTO cash_transactions 
            (user_id, device_id, amount, type, reference_id, notes) 
            VALUES (?, ?, ?, 'sale', ?, ?)");
        $stmt->execute([
            $data['user_id'],
            $deviceId,
            $data['total'], // positive amount = cash IN
            $saleId,
            'Sale ' . $invoiceNo
        ]);
        // ==========================================
        
        $db->commit();
        return ['success' => true, 'sale_id' => $saleId, 'invoice_no' => $invoiceNo];
    } catch (Exception $e) {
        $db->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function getSales($limit = 100, $search = '') {
    $db = Database::getInstance()->getConnection();
    $limit = (int)$limit;
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "s.device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(s.invoice_no LIKE ? OR s.customer_name LIKE ? OR s.customer_phone LIKE ? OR c.name LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    $sql = "SELECT s.*, u.name as cashier, c.name as customer_name_from_db 
            FROM sales s 
            LEFT JOIN users u ON s.user_id = u.id 
            LEFT JOIN customers c ON s.customer_id = c.id";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY s.id DESC LIMIT " . $limit;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getSaleById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT s.*, u.name as cashier, c.name as customer_name_from_db 
                          FROM sales s 
                          LEFT JOIN users u ON s.user_id = u.id 
                          LEFT JOIN customers c ON s.customer_id = c.id 
                          WHERE s.id = ?");
    $stmt->execute([$id]);
    $sale = $stmt->fetch();
    if ($sale) {
        $sale['customer_display_name'] = $sale['customer_name_from_db'] ?? $sale['customer_name'] ?? 'Walk-in';
        $stmt = $db->prepare("SELECT si.*, p.name as product_name FROM sale_items si 
                              LEFT JOIN products p ON si.product_id = p.id 
                              WHERE si.sale_id = ?");
        $stmt->execute([$id]);
        $sale['items'] = $stmt->fetchAll();
    }
    return $sale;
}

// ============================================
// DASHBOARD FUNCTIONS
// ============================================
function getDashboardStats() {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $deviceFilter = $deviceId ? "device_id = $deviceId" : "1=1";

    $stats = [];

    $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE $deviceFilter");
    $stats['total_products'] = $stmt->fetch()['count'];

    $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total FROM sales WHERE DATE(created_at) = CURDATE() AND $deviceFilter");
    $stats['today_sales'] = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as count FROM sales WHERE DATE(created_at) = CURDATE() AND $deviceFilter");
    $stats['today_orders'] = $stmt->fetch()['count'];

    $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE stock <= min_stock AND $deviceFilter");
    $stats['low_stock'] = $stmt->fetch()['count'];

    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE $deviceFilter");
    $stats['total_users'] = $stmt->fetch()['count'];

    return $stats;
}

// ============================================
// LANGUAGE & TRANSLATION FUNCTIONS
// ============================================
function __($key) {
    $lang = getCurrentLanguage();
    $langFile = __DIR__ . "/../lang/{$lang}.php";
    if (file_exists($langFile)) {
        $translations = include $langFile;
        if (isset($translations[$key])) {
            return $translations[$key];
        }
    }
    return $key; // fallback
}

function t($en, $ar) {
    return getCurrentLanguage() === 'ar' ? $ar : $en;
}

function getCurrentLanguage() {
    return $_SESSION['lang'] ?? 'en';
}

function setLanguage($lang) {
    $_SESSION['lang'] = $lang;
    $_SESSION['dir'] = $lang === 'ar' ? 'rtl' : 'ltr';
}

// ============================================
// REPORT FUNCTIONS
// ============================================
function getSalesSummary($period = 'today') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $deviceFilter = $deviceId ? "device_id = $deviceId" : "1=1";

    switch ($period) {
        case 'today':
            $dateCondition = "DATE(created_at) = CURDATE()";
            break;
        case 'week':
            $dateCondition = "YEARWEEK(created_at) = YEARWEEK(CURDATE())";
            break;
        case 'month':
            $dateCondition = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
            break;
        default:
            $dateCondition = "1=1";
    }

    $stmt = $db->query("SELECT 
        COALESCE(SUM(total), 0) as total_sales,
        COUNT(*) as total_orders,
        COALESCE(AVG(total), 0) as average_order,
        COALESCE(SUM(discount), 0) as total_discounts
        FROM sales WHERE $dateCondition AND $deviceFilter");

    return $stmt->fetch();
}

function getTopProducts($limit = 10) {
    $db = Database::getInstance()->getConnection();
    $limit = (int)$limit;
    $deviceId = getCurrentDeviceId();
    $deviceFilter = $deviceId ? "p.device_id = $deviceId" : "1=1";

    $sql = "SELECT 
        p.id, p.name, p.price, p.stock,
        COALESCE(SUM(si.quantity), 0) as total_sold,
        COALESCE(SUM(si.total), 0) as total_revenue
        FROM products p
        LEFT JOIN sale_items si ON p.id = si.product_id
        WHERE $deviceFilter
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT " . $limit;

    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getLowStockProducts($threshold = null) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $deviceFilter = $deviceId ? "device_id = $deviceId" : "1=1";

    if ($threshold) {
        $stmt = $db->prepare("SELECT * FROM products WHERE stock <= ? AND is_active = 1 AND $deviceFilter ORDER BY stock ASC");
        $stmt->execute([$threshold]);
    } else {
        $stmt = $db->query("SELECT * FROM products WHERE stock <= min_stock AND is_active = 1 AND $deviceFilter ORDER BY stock ASC");
    }
    return $stmt->fetchAll();
}

function getProfitReport($period = 'today') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $deviceFilter = $deviceId ? "s.device_id = $deviceId" : "1=1";

    switch ($period) {
        case 'today':
            $dateCondition = "DATE(s.created_at) = CURDATE()";
            break;
        case 'week':
            $dateCondition = "YEARWEEK(s.created_at) = YEARWEEK(CURDATE())";
            break;
        case 'month':
            $dateCondition = "MONTH(s.created_at) = MONTH(CURDATE()) AND YEAR(s.created_at) = YEAR(CURDATE())";
            break;
        default:
            $dateCondition = "1=1";
    }

    $stmt = $db->query("SELECT 
        COALESCE(SUM(si.total), 0) as revenue,
        COALESCE(SUM(si.quantity * p.cost), 0) as cost,
        COALESCE(SUM(si.total) - SUM(si.quantity * p.cost), 0) as profit
        FROM sales s
        LEFT JOIN sale_items si ON s.id = si.sale_id
        LEFT JOIN products p ON si.product_id = p.id
        WHERE $dateCondition AND $deviceFilter");

    return $stmt->fetch();
}

// ============================================
// SETTINGS FUNCTIONS
// ============================================
function getSetting($key) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT value FROM settings WHERE `key` = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['value'] : null;
}

function getSettings($keys = null) {
    $db = Database::getInstance()->getConnection();
    if ($keys && is_array($keys)) {
        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $db->prepare("SELECT `key`, value FROM settings WHERE `key` IN ($placeholders)");
        $stmt->execute($keys);
    } else {
        $stmt = $db->query("SELECT `key`, value FROM settings");
    }
    $result = $stmt->fetchAll();
    $settings = [];
    foreach ($result as $row) {
        $settings[$row['key']] = $row['value'];
    }
    return $settings;
}

function updateSetting($key, $value) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
    return $stmt->execute([$value, $key]);
}

function updateSettings($data) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    try {
        foreach ($data as $key => $value) {
            $stmt = $db->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
            $stmt->execute([$value, $key]);
        }
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function getPrinterSettings() {
    $keys = ['printer_type', 'printer_connection', 'printer_ip', 'printer_port', 'printer_name', 'receipt_copies'];
    return getSettings($keys);
}

// ============================================
// GET RECEIPT TEMPLATE
// ============================================
function getReceiptTemplate($templateId = null) {
    $db = Database::getInstance()->getConnection();
    
    if ($templateId) {
        $stmt = $db->prepare("SELECT * FROM receipt_templates WHERE id = ?");
        $stmt->execute([$templateId]);
        $template = $stmt->fetch();
        if ($template) {
            return $template;
        }
    }
    
    // Get default template
    $stmt = $db->query("SELECT * FROM receipt_templates WHERE is_default = 1 LIMIT 1");
    $template = $stmt->fetch();
    if ($template) {
        return $template;
    }
    
    // Fallback: get first template
    $stmt = $db->query("SELECT * FROM receipt_templates LIMIT 1");
    $template = $stmt->fetch();
    if ($template) {
        return $template;
    }
    
    // No template exists — create default
    $defaultSettings = [
        'direction' => 'ltr',
        'paper_width' => 40,
        'font_size' => 12,
        'font_weight' => 'normal',
        'footer_text' => 'Thank you for your business!',
        'store_name' => ['enabled' => true],
        'store_address' => ['enabled' => true],
        'store_phone' => ['enabled' => true],
        'invoice_no' => ['enabled' => true],
        'date' => ['enabled' => true],
        'cashier' => ['enabled' => true],
        'customer' => ['enabled' => true],
        'items_table' => ['enabled' => true, 'border_style' => 'box'],
        'subtotal' => ['enabled' => true],
        'discount' => ['enabled' => true],
        'tax' => ['enabled' => true],
        'total' => ['enabled' => true],
        'footer' => ['enabled' => true]
    ];
    $stmt = $db->prepare("INSERT INTO receipt_templates (name, is_default, settings) VALUES (?, 1, ?)");
    $stmt->execute(['Default Template', json_encode($defaultSettings)]);
    $id = $db->lastInsertId();
    
    return ['id' => $id, 'settings' => $defaultSettings];
}

// ============================================
// PRINTING FUNCTIONS
// ============================================
function printReceipt($saleId, $method = 'normal') {
    // Always generate the PDF too — used as the manual fallback / "view receipt"
    // path regardless of whether physical bridge printing succeeds.
    $result = generateReceiptPDF($saleId, 'normal');
    if (!$result['success']) {
        return ['success' => false, 'message' => $result['message']];
    }

    $response = [
        'success' => true,
        'file' => '/pos/receipts/' . basename($result['file']),
        'pdf_base64' => base64_encode(file_get_contents($result['file'])),
        'printed' => null,        // null = no bridge configured, so nothing was attempted
        'print_message' => null,
    ];

    $settings = getSettings();
    $printerMethod = $settings['printer_method'] ?? 'pdf';
    $printerName = $settings['printer_name'] ?? '';

    if ($printerMethod === 'pdf') {
        $sumatraPath = $settings['sumatra_path'] ?? '';
        if ($sumatraPath !== '' && $printerName !== '') {
            $bridgeResult = printViaSumatraPdf($result['file'], $sumatraPath, $printerName);
            $response['printed'] = $bridgeResult['success'];
            $response['print_message'] = $bridgeResult['message'];
        }
    } elseif ($printerMethod === 'windows') {
        $bridgePath = $settings['printer_bridge_path'] ?? '';
        if ($bridgePath !== '' && $printerName !== '') {
            $bridgeResult = printViaTextPrinterBridge($saleId, $bridgePath, $printerName);
            $response['printed'] = $bridgeResult['success'];
            $response['print_message'] = $bridgeResult['message'];
        }
    }

    return $response;
}

// ============================================
// PHYSICAL PRINTING — prints the exact generated PDF (barcode, fonts, full
// layout) via SumatraPDF's silent command-line printing. This is the
// recommended method: since the PDF is generated by mpdf, which already
// shapes Arabic text correctly, printing the PDF pixel-for-pixel sidesteps
// the GDI+ text-drawing/Arabic-shaping problem entirely — no need to
// reconstruct the receipt as plain text at all.
// Requires SumatraPDF (portable build is fine, no install needed):
// https://www.sumatrapdfreader.org/download-free-pdf-viewer
// ============================================
function printViaSumatraPdf($pdfFilePath, $sumatraPath, $printerName) {
    if (!file_exists($sumatraPath)) {
        return ['success' => false, 'message' => "SumatraPDF not found at: $sumatraPath"];
    }
    if (!file_exists($pdfFilePath)) {
        return ['success' => false, 'message' => "Receipt PDF not found at: $pdfFilePath"];
    }

    // -print-settings "noscale" keeps the PDF's own physical page size
    // (our receipt PDFs are already generated at 80mm width to match the
    // printer roll) instead of Sumatra auto-fitting/shrinking it to
    // whatever default paper size Windows reports for the printer.
    $cmd = escapeshellarg($sumatraPath)
        . ' -print-to ' . escapeshellarg($printerName)
        . ' -print-settings ' . escapeshellarg('noscale')
        . ' -exit-on-print '
        . escapeshellarg($pdfFilePath);

    exec($cmd . ' 2>&1', $outputLines, $exitCode);

    if ($exitCode === 0) {
        return ['success' => true, 'message' => 'Printed successfully.'];
    }

    $errorDetail = trim(implode("\n", $outputLines));
    return ['success' => false, 'message' => $errorDetail !== '' ? $errorDetail : "SumatraPDF exited with code $exitCode."];
}

// ============================================
// PHYSICAL PRINTING via the TextPrinter.exe Windows-driver bridge
// (needed for correct Arabic rendering — see TextPrinter's PrintToPrinter,
// which uses GDI+/Graphics.DrawString through a real Windows printer driver
// instead of raw ESC/POS text, which cannot shape Arabic correctly).
// ============================================
function printViaTextPrinterBridge($saleId, $bridgePath, $printerName) {
    if (!file_exists($bridgePath)) {
        return ['success' => false, 'message' => "Bridge executable not found at: $bridgePath"];
    }

    $sale = getSaleById($saleId);
    if (!$sale) {
        return ['success' => false, 'message' => 'Sale not found for printing.'];
    }

    $text = buildTextReceipt($sale);

    // Write to a temp file rather than passing text as a CLI argument —
    // multi-line Arabic content is not safe to pass reliably through
    // shell argument escaping, and TextPrinter.exe already accepts a
    // file path (it does File.Exists() itself, reading as UTF-8).
    $receiptDir = __DIR__ . '/../receipts';
    if (!is_dir($receiptDir)) {
        mkdir($receiptDir, 0777, true);
    }
    $tempFile = $receiptDir . '/print_job_' . $saleId . '_' . uniqid() . '.txt';
    file_put_contents($tempFile, $text, LOCK_EX);

    $cmd = escapeshellarg($bridgePath) . ' ' . escapeshellarg($printerName) . ' ' . escapeshellarg($tempFile);
    exec($cmd . ' 2>&1', $outputLines, $exitCode);

    // Best-effort cleanup — don't let a failed delete affect the print result.
    @unlink($tempFile);

    if ($exitCode === 0) {
        return ['success' => true, 'message' => 'Printed successfully.'];
    }

    // TextPrinter.exe writes "Print error: {message}" to stderr on failure,
    // which exec() captured above (2>&1) — surface it directly so a wrong
    // printer name / offline printer / etc. is visible instead of silent.
    $errorDetail = trim(implode("\n", $outputLines));
    return ['success' => false, 'message' => $errorDetail !== '' ? $errorDetail : "Bridge exited with code $exitCode."];
}


// ============================================
// CUSTOMER FUNCTIONS (with device filter)
// ============================================
function getAllCustomers($search = '') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR phone LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql = "SELECT * FROM customers";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY name";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCustomerById($id) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("SELECT * FROM customers WHERE id = ? AND device_id = ?");
    $stmt->execute([$id, $deviceId]);
    return $stmt->fetch();
}

function getCustomerSales($customerId) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("SELECT * FROM sales WHERE customer_id = ? AND device_id = ? ORDER BY id DESC LIMIT 50");
    $stmt->execute([$customerId, $deviceId]);
    return $stmt->fetchAll();
}

function createCustomer($data) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("INSERT INTO customers (device_id, name, phone, email, address, notes) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $deviceId,
        $data['name'],
        $data['phone'] ?? null,
        $data['email'] ?? null,
        $data['address'] ?? null,
        $data['notes'] ?? null
    ]);
}

function updateCustomer($id, $data) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("UPDATE customers SET name = ?, phone = ?, email = ?, address = ?, notes = ? WHERE id = ? AND device_id = ?");
    return $stmt->execute([
        $data['name'],
        $data['phone'] ?? null,
        $data['email'] ?? null,
        $data['address'] ?? null,
        $data['notes'] ?? null,
        $id,
        $deviceId
    ]);
}

function deleteCustomer($id) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("DELETE FROM customers WHERE id = ? AND device_id = ?");
    return $stmt->execute([$id, $deviceId]);
}

// ============================================
// EXPENSE FUNCTIONS (with device filter)
// ============================================
function getExpenseCategories() {
    // Use settings-based approach (already implemented)
    $categories = getSetting('expense_categories');
    if ($categories) {
        return explode(',', $categories);
    }
    return ['Rent', 'Utilities', 'Salaries', 'Supplies', 'Maintenance', 'Marketing', 'Transportation', 'Insurance', 'Other'];
}

function getAllExpenses($startDate = null, $endDate = null, $search = '') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "e.device_id = ?";
        $params[] = $deviceId;
    }
    if ($startDate) {
        $conditions[] = "e.expense_date >= ?";
        $params[] = $startDate;
    }
    if ($endDate) {
        $conditions[] = "e.expense_date <= ?";
        $params[] = $endDate;
    }
    if (!empty($search)) {
        $conditions[] = "(e.description LIKE ? OR e.category LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql = "SELECT e.*, u.name as user_name 
            FROM expenses e 
            LEFT JOIN users u ON e.created_by = u.id"; // FIX: created_by not user_id

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY e.expense_date DESC, e.id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getExpenseById($id) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("SELECT e.*, u.name as user_name 
                          FROM expenses e 
                          LEFT JOIN users u ON e.created_by = u.id 
                          WHERE e.id = ? AND e.device_id = ?");
    $stmt->execute([$id, $deviceId]);
    return $stmt->fetch();
}

function getExpenseSummary($startDate = null, $endDate = null) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }
    if ($startDate) {
        $conditions[] = "expense_date >= ?";
        $params[] = $startDate;
    }
    if ($endDate) {
        $conditions[] = "expense_date <= ?";
        $params[] = $endDate;
    }

    // Total expenses
    $sql = "SELECT COALESCE(SUM(amount), 0) as total, COUNT(*) as count FROM expenses";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $overall = $stmt->fetch();

    // Category breakdown
    $sql = "SELECT category, COALESCE(SUM(amount), 0) as total, COUNT(*) as count 
            FROM expenses";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " GROUP BY category ORDER BY total DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $categoryTotals = $stmt->fetchAll();

    return [
        'total' => $overall['total'] ?? 0,
        'count' => $overall['count'] ?? 0,
        'by_category' => $categoryTotals
    ];
}

function createExpense($data) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("INSERT INTO expenses (device_id, category, amount, description, expense_date, created_by, payment_method) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $deviceId,
        $data['category'],
        $data['amount'],
        $data['description'] ?? null,
        $data['expense_date'],
        $data['user_id'] ?? 0, // This maps to created_by
        $data['payment_method'] ?? 'cash'
    ]);
}

function updateExpense($id, $data) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("UPDATE expenses SET 
        category = ?, amount = ?, description = ?, expense_date = ?, payment_method = ? 
        WHERE id = ? AND device_id = ?");
    return $stmt->execute([
        $data['category'],
        $data['amount'],
        $data['description'] ?? null,
        $data['expense_date'],
        $data['payment_method'] ?? 'cash',
        $id,
        $deviceId
    ]);
}

function deleteExpense($id) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("DELETE FROM expenses WHERE id = ? AND device_id = ?");
    return $stmt->execute([$id, $deviceId]);
}

// ============================================
// RETURNS FUNCTIONS
// ============================================
function createReturn($data) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    
    try {
        $returnNo = 'RET-' . date('Ymd') . '-' . rand(1000, 9999);
        $returnType = isset($data['sale_id']) && $data['sale_id'] > 0 ? 'invoice' : 'walkin';
        $deviceId = getCurrentDeviceId();
        
        // Ensure total_refund is a float, default 0
        $totalRefund = isset($data['total_refund']) ? (float)$data['total_refund'] : 0;
        $user_id = $data['user_id'];
        
        $stmt = $db->prepare("INSERT INTO returns 
            (sale_id, return_no, return_type, customer_name, customer_phone, user_id, reason, refund_method, total_refund, notes, device_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['sale_id'] ?? null,
            $returnNo,
            $returnType,
            $data['customer_name'] ?? null,
            $data['customer_phone'] ?? null,
            $user_id,
            $data['reason'] ?? null,
            $data['refund_method'] ?? 'cash',
            $totalRefund,
            $data['notes'] ?? null,
            $deviceId
        ]);
        
        $returnId = $db->lastInsertId();
        
        // Insert return items and restock
        foreach ($data['items'] as $item) {
            $saleItemId = $item['sale_item_id'] ?? null;
            $stmt = $db->prepare("INSERT INTO return_items (return_id, sale_item_id, product_id, quantity, refund_amount, reason) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $returnId,
                $saleItemId,
                $item['product_id'],
                $item['quantity'],
                $item['refund_amount'],
                $item['reason'] ?? null
            ]);
            // Restock product
            $stmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // ==========================================
        // 🔥 ADD CASH TRANSACTION (CASH OUT)
        // ==========================================
        $stmt = $db->prepare("INSERT INTO cash_transactions 
            (user_id, device_id, amount, type, reference_id, notes) 
            VALUES (?, ?, ?, 'return', ?, ?)");
        $stmt->execute([
            $user_id,
            $deviceId,
            -$totalRefund, // negative = cash OUT (money given back to customer)
            $returnId,
            'Return for ' . $returnNo
        ]);
        // ==========================================
        
        // Update sale return status if linked
        if (isset($data['sale_id']) && $data['sale_id'] > 0) {
            $isFullReturn = ($data['is_full'] ?? false);
            $returnStatus = $isFullReturn ? 'full' : 'partial';
            $stmt = $db->prepare("UPDATE sales SET return_status = ?, return_total = ? WHERE id = ?");
            $stmt->execute([$returnStatus, $totalRefund, $data['sale_id']]);
        }
        
        $db->commit();
        return ['success' => true, 'return_id' => $returnId, 'return_no' => $returnNo];
    } catch (Exception $e) {
        $db->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function getReturnableSaleItems($saleId) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT si.*, p.name as product_name,
                         (SELECT COALESCE(SUM(quantity), 0) FROM return_items WHERE sale_item_id = si.id) as returned_qty
                         FROM sale_items si
                         LEFT JOIN products p ON si.product_id = p.id
                         WHERE si.sale_id = ?
                         HAVING returned_qty < si.quantity");
    $stmt->execute([$saleId]);
    return $stmt->fetchAll();
}

function searchProductsForReturn($search) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $stmt = $db->prepare("SELECT id, name, barcode, price, stock FROM products 
                          WHERE (name LIKE ? OR barcode LIKE ? OR barcode2 LIKE ? OR barcode3 LIKE ? 
                                 OR alameen_code LIKE ? OR coded_code LIKE ?) AND device_id = ? AND is_active = 1
                          LIMIT 20");
    $searchTerm = "%$search%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $deviceId]);
    return $stmt->fetchAll();
}

// ============================================
// INVENTORY FUNCTIONS
// ============================================
function getInventoryData($priceField = 'price', $limit = 50, $offset = 0, $search = '') {
    $db = Database::getInstance()->getConnection();
    $limit = (int)$limit;
    $offset = (int)$offset;
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR barcode LIKE ? OR barcode2 LIKE ? OR barcode3 LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql = "SELECT id, name, barcode, stock, min_stock, 
                   price, price_whole, price_half, price_retail, price_enduser,
                   price2_whole, price2_half, price2_retail, price2_enduser,
                   price3_whole, price3_half, price3_retail, price3_enduser
            FROM products";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY name LIMIT $limit OFFSET $offset";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getTotalInventoryCount($search = '') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $params = [];
    $conditions = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR barcode LIKE ? OR barcode2 LIKE ? OR barcode3 LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql = "SELECT COUNT(*) as total FROM products";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return (int)$result['total'];
}

function getInventoryTotals($priceField = 'price', $search = '') {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $conditions = [];
    $params = [];

    if ($deviceId) {
        $conditions[] = "device_id = ?";
        $params[] = $deviceId;
    }

    if (!empty($search)) {
        $conditions[] = "(name LIKE ? OR barcode LIKE ? OR barcode2 LIKE ? OR barcode3 LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql = "SELECT SUM(stock) as total_stock, SUM(stock * $priceField) as total_value FROM products";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// ============================================
// DEVICE CRUD FUNCTIONS
// ============================================
function getAllDevices($search = '') {
    $db = Database::getInstance()->getConnection();
    if (!empty($search)) {
        $stmt = $db->prepare("SELECT d.*, COUNT(u.id) as user_count 
                              FROM devices d 
                              LEFT JOIN users u ON u.device_id = d.id 
                              WHERE d.device_name LIKE ? OR d.device_code LIKE ? 
                              GROUP BY d.id 
                              ORDER BY d.device_name");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm]);
    } else {
        $stmt = $db->query("SELECT d.*, COUNT(u.id) as user_count 
                            FROM devices d 
                            LEFT JOIN users u ON u.device_id = d.id 
                            GROUP BY d.id 
                            ORDER BY d.device_name");
    }
    return $stmt->fetchAll();
}

function getDeviceById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM devices WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getDeviceUsers($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE device_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch()['count'];
}

function createDevice($data) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO devices (device_name, device_code, is_active) VALUES (?, ?, ?)");
    return $stmt->execute([$data['device_name'], $data['device_code'], $data['is_active'] ?? 1]);
}

function updateDevice($id, $data) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE devices SET device_name = ?, device_code = ?, is_active = ? WHERE id = ?");
    return $stmt->execute([$data['device_name'], $data['device_code'], $data['is_active'] ?? 1, $id]);
}

function deleteDevice($id) {
    $db = Database::getInstance()->getConnection();
    // Unassign users first
    $stmt = $db->prepare("UPDATE users SET device_id = NULL WHERE device_id = ?");
    $stmt->execute([$id]);
    $stmt = $db->prepare("DELETE FROM devices WHERE id = ?");
    return $stmt->execute([$id]);
}
// ============================================
// TRANSFER FUNCTIONS
// ============================================
function createTransfer($data) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    
    try {
        $transferNo = 'TRF-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $stmt = $db->prepare("INSERT INTO transfers (transfer_no, from_device_id, to_device_id, user_id, notes) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $transferNo,
            $data['from_device_id'],
            $data['to_device_id'],
            $data['user_id'],
            $data['notes'] ?? null
        ]);
        
        $transferId = $db->lastInsertId();
        
        foreach ($data['items'] as $item) {
            $stmt = $db->prepare("INSERT INTO transfer_items (transfer_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$transferId, $item['product_id'], $item['quantity'], $item['price'] ?? 0]);
            
            // Subtract from source
            $stmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND device_id = ?");
            $stmt->execute([$item['quantity'], $item['product_id'], $data['from_device_id']]);
            
            // Add to destination
            // FIX: Use `id` instead of `product_id`
            $stmt = $db->prepare("SELECT id, stock FROM products WHERE id = ? AND device_id = ?");
            $stmt->execute([$item['product_id'], $data['to_device_id']]);
            $destProduct = $stmt->fetch();
            
            if ($destProduct) {
                $stmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $destProduct['id']]);
            } else {
                $stmt = $db->prepare("INSERT INTO products (device_id, name, barcode, description, price, cost, stock, min_stock, category_id, is_active) 
                                      SELECT ?, name, barcode, description, price, cost, ?, min_stock, category_id, is_active 
                                      FROM products WHERE id = ?");
                $stmt->execute([$data['to_device_id'], $item['quantity'], $item['product_id']]);
            }
        }
        
        $db->commit();
        return ['success' => true, 'transfer_id' => $transferId, 'transfer_no' => $transferNo];
    } catch (Exception $e) {
        $db->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function getTransfers($limit = 50) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $limit = (int)$limit; // Ensure it's an integer
    
    $stmt = $db->prepare("SELECT t.*, 
                          d1.device_name as from_device, 
                          d2.device_name as to_device,
                          u.name as user_name
                          FROM transfers t
                          LEFT JOIN devices d1 ON t.from_device_id = d1.id
                          LEFT JOIN devices d2 ON t.to_device_id = d2.id
                          LEFT JOIN users u ON t.user_id = u.id
                          WHERE t.from_device_id = ? OR t.to_device_id = ?
                          ORDER BY t.id DESC LIMIT " . $limit);
    $stmt->execute([$deviceId, $deviceId]);
    return $stmt->fetchAll();
}

function getTransferById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT t.*, 
                          d1.device_name as from_device, 
                          d2.device_name as to_device
                          FROM transfers t
                          LEFT JOIN devices d1 ON t.from_device_id = d1.id
                          LEFT JOIN devices d2 ON t.to_device_id = d2.id
                          WHERE t.id = ?");
    $stmt->execute([$id]);
    $transfer = $stmt->fetch();
    if ($transfer) {
        $stmt = $db->prepare("SELECT ti.*, p.name as product_name 
                              FROM transfer_items ti 
                              LEFT JOIN products p ON ti.product_id = p.id 
                              WHERE ti.transfer_id = ?");
        $stmt->execute([$id]);
        $transfer['items'] = $stmt->fetchAll();
    }
    return $transfer;
}
// ============================================
// CURRENCY HELPERS
// ============================================
function getCurrencySymbol() {
    $symbol = getSetting('currency_symbol');
    return $symbol ?: '$';
}

function formatPrice($amount) {
    $symbol = getCurrencySymbol();
    $formatted = number_format((float)$amount, 2, '.', ',');
    
    if (getCurrentLanguage() === 'ar') {
        // Arabic: symbol on the left
        return $symbol . ' ' . $formatted;
    } else {
        // English: symbol on the right
        return $formatted . ' ' . $symbol;
    }
}

// ============================================
// SUPPLIER FUNCTIONS
// ============================================
function getSuppliers($search = '') {
    $db = Database::getInstance()->getConnection();
    if (!empty($search)) {
        $stmt = $db->prepare("SELECT * FROM suppliers WHERE name LIKE ? OR phone LIKE ? OR email LIKE ? ORDER BY name");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $db->query("SELECT * FROM suppliers ORDER BY name");
    }
    return $stmt->fetchAll();
}

function getSupplierById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createSupplier($data) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO suppliers (name, phone, email, address, contact_person, notes) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['name'],
        $data['phone'] ?? null,
        $data['email'] ?? null,
        $data['address'] ?? null,
        $data['contact_person'] ?? null,
        $data['notes'] ?? null
    ]);
}

function updateSupplier($id, $data) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE suppliers SET name = ?, phone = ?, email = ?, address = ?, contact_person = ?, notes = ? WHERE id = ?");
    return $stmt->execute([
        $data['name'],
        $data['phone'] ?? null,
        $data['email'] ?? null,
        $data['address'] ?? null,
        $data['contact_person'] ?? null,
        $data['notes'] ?? null,
        $id
    ]);
}

function deleteSupplier($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM suppliers WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============================================
// PURCHASE ORDER FUNCTIONS
// ============================================
function createPurchaseOrder($data) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    try {
        $poNo = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
        $deviceId = getCurrentDeviceId();
        $userId = $_SESSION['user_id'];
        
        $stmt = $db->prepare("INSERT INTO purchase_orders (po_no, supplier_id, user_id, device_id, order_date, expected_delivery, notes) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $poNo,
            $data['supplier_id'],
            $userId,
            $deviceId,
            $data['order_date'],
            $data['expected_delivery'] ?? null,
            $data['notes'] ?? null
        ]);
        $poId = $db->lastInsertId();
        
        $total = 0;
        foreach ($data['items'] as $item) {
            $totalItem = $item['quantity'] * $item['unit_price'];
            $total += $totalItem;
            $stmt = $db->prepare("INSERT INTO purchase_order_items (po_id, product_id, quantity, unit_price, total) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$poId, $item['product_id'], $item['quantity'], $item['unit_price'], $totalItem]);
        }
        
        // Update total in purchase order
        $stmt = $db->prepare("UPDATE purchase_orders SET total_amount = ? WHERE id = ?");
        $stmt->execute([$total, $poId]);
        
        $db->commit();
        return ['success' => true, 'po_id' => $poId, 'po_no' => $poNo];
    } catch (Exception $e) {
        $db->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// ============================================
// UPDATE PURCHASE ORDER
// Only pending orders belonging to the current device can be edited.
// Receiving/cancelling/deleting keeps the existing behavior unchanged.
// ============================================
function updatePurchaseOrder($data) {
    $db = Database::getInstance()->getConnection();
    $id = (int)($data['id'] ?? 0);
    $supplierId = (int)($data['supplier_id'] ?? 0);
    $orderDate = trim($data['order_date'] ?? '');
    $expectedDelivery = !empty($data['expected_delivery']) ? $data['expected_delivery'] : null;
    $notes = $data['notes'] ?? null;
    $items = $data['items'] ?? [];
    $deviceId = getCurrentDeviceId();

    if ($id <= 0 || $supplierId <= 0 || !$orderDate || !is_array($items) || count($items) === 0) {
        return ['success' => false, 'message' => 'Invalid purchase order data.'];
    }

    foreach ($items as $item) {
        $productId = (int)($item['product_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);
        $unitPrice = (float)($item['unit_price'] ?? -1);

        if ($productId <= 0 || $quantity <= 0 || $unitPrice < 0) {
            return ['success' => false, 'message' => 'Invalid product, quantity or unit price.'];
        }
    }

    try {
        $db->beginTransaction();

        // Lock the PO and make absolutely sure it is still editable.
        $stmt = $db->prepare("SELECT * FROM purchase_orders WHERE id = ? AND device_id = ? FOR UPDATE");
        $stmt->execute([$id, $deviceId]);
        $po = $stmt->fetch();

        if (!$po) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Purchase order not found.'];
        }

        if ($po['status'] !== 'pending') {
            $db->rollBack();
            return ['success' => false, 'message' => 'Only pending purchase orders can be edited.'];
        }

        // Validate supplier belongs to an existing supplier record.
        $stmt = $db->prepare("SELECT id FROM suppliers WHERE id = ? LIMIT 1");
        $stmt->execute([$supplierId]);
        if (!$stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Supplier not found.'];
        }

        // Validate all products before changing anything.
        $productCheck = $db->prepare("SELECT id FROM products WHERE id = ? AND device_id = ? LIMIT 1");
        foreach ($items as $item) {
            $productId = (int)$item['product_id'];
            $productCheck->execute([$productId, $deviceId]);
            if (!$productCheck->fetch()) {
                $db->rollBack();
                return ['success' => false, 'message' => 'One or more selected products were not found on this device.'];
            }
        }

        // Update PO header. PO number and creator remain unchanged.
        $stmt = $db->prepare("UPDATE purchase_orders
                              SET supplier_id = ?, order_date = ?, expected_delivery = ?, notes = ?, total_amount = 0
                              WHERE id = ? AND device_id = ? AND status = 'pending'");
        $stmt->execute([$supplierId, $orderDate, $expectedDelivery, $notes, $id, $deviceId]);

        // Replace the pending PO's item list atomically.
        $stmt = $db->prepare("DELETE FROM purchase_order_items WHERE po_id = ?");
        $stmt->execute([$id]);

        $insert = $db->prepare("INSERT INTO purchase_order_items
                                (po_id, product_id, quantity, unit_price, total, received_quantity)
                                VALUES (?, ?, ?, ?, ?, 0)");

        $total = 0;
        foreach ($items as $item) {
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            $unitPrice = round((float)$item['unit_price'], 2);
            $lineTotal = round($quantity * $unitPrice, 2);
            $total += $lineTotal;
            $insert->execute([$id, $productId, $quantity, $unitPrice, $lineTotal]);
        }

        $stmt = $db->prepare("UPDATE purchase_orders SET total_amount = ? WHERE id = ? AND device_id = ?");
        $stmt->execute([round($total, 2), $id, $deviceId]);

        $db->commit();
        return [
            'success' => true,
            'message' => 'Purchase order updated successfully.',
            'po_id' => $id,
            'po_no' => $po['po_no'],
            'total_amount' => round($total, 2)
        ];
    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


function getPurchaseOrders($limit = 50) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();
    $limit = (int)$limit; // Ensure it's an integer
    
    $sql = "SELECT po.*, s.name as supplier_name, u.name as created_by 
            FROM purchase_orders po
            LEFT JOIN suppliers s ON po.supplier_id = s.id
            LEFT JOIN users u ON po.user_id = u.id
            WHERE po.device_id = ?
            ORDER BY po.id DESC LIMIT " . $limit;
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$deviceId]);
    return $stmt->fetchAll();
}

function getPurchaseOrderById($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT po.*, s.name as supplier_name, u.name as created_by 
                          FROM purchase_orders po
                          LEFT JOIN suppliers s ON po.supplier_id = s.id
                          LEFT JOIN users u ON po.user_id = u.id
                          WHERE po.id = ?");
    $stmt->execute([$id]);
    $po = $stmt->fetch();
    if ($po) {
        $stmt = $db->prepare("SELECT poi.*, p.name as product_name 
                              FROM purchase_order_items poi
                              LEFT JOIN products p ON poi.product_id = p.id
                              WHERE poi.po_id = ?");
        $stmt->execute([$id]);
        $po['items'] = $stmt->fetchAll();
    }
    return $po;
}

function receivePurchaseOrder($id) {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    try {
        // Get the purchase order and its items
        $po = getPurchaseOrderById($id);
        if (!$po || $po['status'] === 'received') {
            return ['success' => false, 'message' => 'Order not found or already received.'];
        }
        
        // Update stock for each item
        foreach ($po['items'] as $item) {
            $stmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
            // Update received_quantity
            $stmt = $db->prepare("UPDATE purchase_order_items SET received_quantity = quantity WHERE id = ?");
            $stmt->execute([$item['id']]);
        }
        
        // Mark order as received
        $stmt = $db->prepare("UPDATE purchase_orders SET status = 'received' WHERE id = ?");
        $stmt->execute([$id]);
        
        $db->commit();
        return ['success' => true, 'message' => 'Order received and stock updated.'];
    } catch (Exception $e) {
        $db->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function cancelPurchaseOrder($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE purchase_orders SET status = 'cancelled' WHERE id = ? AND status = 'pending'");
    if ($stmt->execute([$id]) && $stmt->rowCount() > 0) {
        return ['success' => true, 'message' => 'Order cancelled.'];
    }
    return ['success' => false, 'message' => 'Order not found or already processed.'];
}

function deletePurchaseOrder($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM purchase_orders WHERE id = ? AND status = 'pending'");
    if ($stmt->execute([$id]) && $stmt->rowCount() > 0) {
        return ['success' => true, 'message' => 'Order deleted.'];
    }
    return ['success' => false, 'message' => 'Only pending orders can be deleted.'];
}

function getInstalledPrinters() {
    // Check if we have a cached list in session
    if (isset($_SESSION['cached_printers']) && isset($_SESSION['cached_printers_time'])) {
        // Cache for 5 minutes
        if (time() - $_SESSION['cached_printers_time'] < 300) {
            return $_SESSION['cached_printers'];
        }
    }
    
    $printers = [];
    
    // Use PowerShell
    $output = shell_exec('powershell -command "Get-WmiObject -Class Win32_Printer | Select-Object -ExpandProperty Name"');
    if ($output) {
        $lines = explode("\n", trim($output));
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $printers[] = $line;
            }
        }
    }
    
    // Fallback to wmic
    if (empty($printers)) {
        $output = shell_exec('wmic printer get name');
        if ($output) {
            $lines = explode("\n", trim($output));
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && strtolower($line) !== 'name') {
                    $printers[] = $line;
                }
            }
        }
    }
    
    // Cache in session
    $_SESSION['cached_printers'] = $printers;
    $_SESSION['cached_printers_time'] = time();
    
    return $printers;
}

function buildReceiptPreview($data) {
    $settings = buildReceiptSettings($data);
    $isRtl = ($settings['direction'] ?? 'ltr') === 'rtl';
    $paperWidth = intval($settings['paper_width'] ?? 40);
    $fontSize = intval($settings['font_size'] ?? 12);
    $fontWeight = $settings['font_weight'] ?? 'normal';
    $borderStyle = $settings['items_table']['border_style'] ?? 'box';
    $footerText = $settings['footer_text'] ?? 'Thank you for your business!';
    $currency = getCurrencySymbol();
    
    // Column widths
    $itemWidth = intval(($paperWidth - 8) * 0.45);
    $qtyWidth = 5;
    $priceWidth = intval(($paperWidth - 8) * 0.25);
    $totalWidth = intval(($paperWidth - 8) * 0.25);
    $totalCols = $itemWidth + $qtyWidth + $priceWidth + $totalWidth + 5;
    
    // Border characters
    if ($borderStyle === 'box') {
        $topBorder = "┌" . str_repeat("─", $totalCols - 2) . "┐\n";
        $bottomBorder = "└" . str_repeat("─", $totalCols - 2) . "┘\n";
        $line = "├" . str_repeat("─", $totalCols - 2) . "┤\n";
        $doubleLine = "╞" . str_repeat("═", $totalCols - 2) . "╡\n";
        $vBar = "│";
    } else {
        $topBorder = $bottomBorder = $line = $doubleLine = "";
        $vBar = "";
    }
    
    $receipt = "";
    $pad = function($text, $width, $align = 'left') use ($isRtl) {
        $text = mb_substr($text, 0, $width);
        if ($isRtl) {
            return str_pad($text, $width, " ", STR_PAD_LEFT);
        }
        return str_pad($text, $width, " ", STR_PAD_RIGHT);
    };
    
    // === HEADER ===
    $receipt .= $topBorder;
    $receipt .= $vBar . " " . $pad("MY POS STORE", $totalCols - 4) . " " . $vBar . "\n";
    $receipt .= $vBar . " " . $pad("123 Main Street, City", $totalCols - 4) . " " . $vBar . "\n";
    $receipt .= $vBar . " " . $pad("Tel: +1234567890", $totalCols - 4) . " " . $vBar . "\n";
    $receipt .= $line;
    
    // === INVOICE INFO ===
    $receipt .= sprintf($vBar . " %-15s: %-18s " . $vBar . "\n", "Invoice", "INV-20260728-8335");
    $receipt .= sprintf($vBar . " %-15s: %-18s " . $vBar . "\n", "Date", date('Y-m-d H:i:s'));
    $receipt .= sprintf($vBar . " %-15s: %-18s " . $vBar . "\n", "Cashier", "Administrator");
    $receipt .= sprintf($vBar . " %-15s: %-18s " . $vBar . "\n", "Customer", "Walk-in");
    $receipt .= $line;
    
    // === TABLE HEADER ===
    if ($isRtl) {
        $receipt .= sprintf($vBar . " %{$totalWidth}s │ %{$priceWidth}s │ %{$qtyWidth}s │ %-{$itemWidth}s " . $vBar . "\n", 
            "TOTAL", "PRICE", "QTY", "ITEM");
    } else {
        $receipt .= sprintf($vBar . " %-{$itemWidth}s │ %{$qtyWidth}s │ %{$priceWidth}s │ %{$totalWidth}s " . $vBar . "\n", 
            "ITEM", "QTY", "PRICE", "TOTAL");
    }
    $receipt .= $line;

    // === TABLE ROWS (Sample data for preview) ===
    $sampleItems = [
        ['name' => 'Sample Product', 'qty' => 2, 'price' => 10.00, 'total' => 20.00],
        ['name' => 'Another Item', 'qty' => 1, 'price' => 15.00, 'total' => 15.00],
    ];
    
    foreach ($sampleItems as $item) {
        $name = mb_substr($item['name'], 0, $itemWidth);
        $qty = $item['qty'];
        $price = $currency . ' ' . number_format($item['price'], 2);
        $total = $currency . ' ' . number_format($item['total'], 2);
        
        if ($isRtl) {
            $receipt .= sprintf($vBar . " %{$totalWidth}s │ %{$priceWidth}s │ %{$qtyWidth}s │ %-{$itemWidth}s " . $vBar . "\n", 
                $total, $price, $qty, $name);
        } else {
            $receipt .= sprintf($vBar . " %-{$itemWidth}s │ %{$qtyWidth}s │ %{$priceWidth}s │ %{$totalWidth}s " . $vBar . "\n", 
                $name, $qty, $price, $total);
        }
    }
    $receipt .= $line;

    // === TOTALS ===
    $receipt .= sprintf($vBar . " %-24s: %12s " . $vBar . "\n", "Subtotal", $currency . " 35.00");
    $receipt .= sprintf($vBar . " %-24s: %12s " . $vBar . "\n", "Discount", "-" . $currency . " 0.00");
    $receipt .= sprintf($vBar . " %-24s: %12s " . $vBar . "\n", "Tax", $currency . " 0.00");
    $receipt .= $doubleLine;
    $receipt .= sprintf($vBar . " %-24s: %12s " . $vBar . "\n", "TOTAL", $currency . " 35.00");
    $receipt .= $bottomBorder;
    
    // === FOOTER ===
    $receipt .= $vBar . " " . $pad($footerText, $totalCols - 4) . " " . $vBar . "\n";
    $receipt .= $bottomBorder;
    
    return "<pre style='font-family: \"Courier New\", monospace; font-size: {$fontSize}px; font-weight: {$fontWeight}; max-width: {$paperWidth}ch; margin: 0 auto; background: white; padding: 20px; line-height: 1.4;'>" . htmlspecialchars($receipt) . "</pre>";
}

function buildReceiptSettings($data) {
    return [
        'direction' => $data['direction'] ?? 'ltr',
        'paper_width' => intval($data['paper_width'] ?? 40),
        'font_size' => intval($data['font_size'] ?? 12),
        'font_weight' => $data['font_weight'] ?? 'normal',
        'footer_text' => $data['footer_text'] ?? 'Thank you for your business!',
        'store_name' => ['enabled' => isset($data['field_store_name'])],
        'store_address' => ['enabled' => isset($data['field_store_address'])],
        'store_phone' => ['enabled' => isset($data['field_store_phone'])],
        'invoice_no' => ['enabled' => isset($data['field_invoice_no'])],
        'date' => ['enabled' => isset($data['field_date'])],
        'cashier' => ['enabled' => isset($data['field_cashier'])],
        'customer' => ['enabled' => isset($data['field_customer'])],
        'items_table' => [
            'enabled' => true, // Default to enabled if not set
            'border_style' => $data['border_style'] ?? 'box'
        ],
        'subtotal' => ['enabled' => isset($data['field_subtotal'])],
        'discount' => ['enabled' => isset($data['field_discount'])],
        'tax' => ['enabled' => isset($data['field_tax'])],
        'total' => ['enabled' => isset($data['field_total'])],
        'footer' => ['enabled' => isset($data['field_footer'])]
    ];
}

function generateReceiptPDF($saleId, $method = 'normal') {
    $sale = getSaleById($saleId);
    if (!$sale) {
        return ['success' => false, 'message' => 'Sale not found'];
    }
    
    $storeSettings = getSettings();
    $storeName = $storeSettings['store_name'] ?? 'POS System';
    $storeAddress = $storeSettings['store_address'] ?? '';
    $storePhone = $storeSettings['store_phone'] ?? '';
    $currency = getCurrencySymbol();
    $isRtl = getCurrentLanguage() === 'ar';

    // Pull the actual configured receipt template (previously ignored entirely
    // — generateReceiptPDF used to hardcode every field on, regardless of
    // what was saved in the receipt designer).
    $templateRow = getReceiptTemplate();
    $templateSettings = [];
    if (!empty($templateRow['settings'])) {
        $decoded = is_array($templateRow['settings']) ? $templateRow['settings'] : json_decode($templateRow['settings'], true);
        if (is_array($decoded)) {
            $templateSettings = $decoded;
        }
    }
    $fieldEnabled = function ($key) use ($templateSettings) {
        return $templateSettings[$key]['enabled'] ?? true;
    };
    $settings = [
        'direction'      => $templateSettings['direction'] ?? ($isRtl ? 'rtl' : 'ltr'),
        'font_size'      => intval($templateSettings['font_size'] ?? 12),
        'font_weight'    => $templateSettings['font_weight'] ?? 'normal',
        'footer_text'    => $templateSettings['footer_text'] ?? ($storeSettings['receipt_footer'] ?? 'Thank you for your business!'),
        'show_store_name'    => $fieldEnabled('store_name'),
        'show_store_address' => $fieldEnabled('store_address'),
        'show_store_phone'   => $fieldEnabled('store_phone'),
        'show_invoice_no'    => $fieldEnabled('invoice_no'),
        'show_date'          => $fieldEnabled('date'),
        'show_cashier'       => $fieldEnabled('cashier'),
        'show_customer'      => $fieldEnabled('customer'),
        'show_subtotal'      => $fieldEnabled('subtotal'),
        'show_discount'      => $fieldEnabled('discount'),
        'show_tax'           => $fieldEnabled('tax'),
        'show_total'         => $fieldEnabled('total'),
        'show_footer'        => $fieldEnabled('footer'),
    ];
    $footerText = $settings['footer_text'];

    // Barcode of the invoice number, so the printed receipt can be re-scanned
    // (e.g. to pull it back up for a return) instead of typed in by hand.
    // vendor/autoload.php must come first: Picqer's barcode class is
    // Composer-autoloaded, and Barcode.php's class_exists() check would
    // silently fail (and skip the barcode) without it.
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/Barcode.php';
    $invoiceBarcode = generateBarcodeImage($sale['invoice_no']);

    // Load template
    ob_start();
    require __DIR__ . '/../views/receipt_template.php';
    $html = ob_get_clean();
    
    $mpdf = new \Mpdf\Mpdf([
        'mode'          => 'utf-8',
        'format'        => [80, 150], // thermal receipt size
        'margin_top'    => 5,
        'margin_bottom' => 5,
        'margin_left'   => 5,
        'margin_right'  => 5,
        'default_font'  => 'dejavusans',
        'autoScriptToLang' => true,
        'autoLangToFont'   => true,
    ]);
    
    // Optional: register Amiri font if available
    $fontPath = __DIR__ . '/../assets/fonts/Amiri-Regular.ttf';
    $fontBold = __DIR__ . '/../assets/fonts/Amiri-Bold.ttf';
    if (file_exists($fontPath)) {
        $mpdf->fontdata['amiri'] = [
            'R' => $fontPath,
            'B' => file_exists($fontBold) ? $fontBold : $fontPath,
        ];
        $mpdf->default_font = 'amiri';
    }
    
    $mpdf->SetTitle('Receipt ' . $sale['invoice_no']);
    $mpdf->useAdobeCJK = true;
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont = true;
    
    $mpdf->WriteHTML($html);
    
    // Save to a permanent folder for inspection
    $receiptDir = __DIR__ . '/../receipts';
    if (!is_dir($receiptDir)) {
        mkdir($receiptDir, 0777, true);
    }
    $pdfFile = $receiptDir . '/receipt_' . $saleId . '_' . date('Ymd_His') . '.pdf';
    $mpdf->Output($pdfFile, \Mpdf\Output\Destination::FILE);
    
    return ['success' => true, 'file' => $pdfFile];
}

function buildTextReceipt($sale) {
    $settings = getSettings();
    $storeName = $settings['store_name'] ?? 'POS System';
    $storeAddress = $settings['store_address'] ?? '';
    $storePhone = $settings['store_phone'] ?? '';
    $currency = getCurrencySymbol();
    $footer = $settings['receipt_footer'] ?? 'Thank you!';
    
    $receipt = "========================\n";
    $receipt .= "   " . strtoupper($storeName) . "\n";
    if ($storeAddress) $receipt .= "   " . $storeAddress . "\n";
    if ($storePhone) $receipt .= "   Tel: " . $storePhone . "\n";
    $receipt .= "========================\n";
    $receipt .= "Invoice: " . $sale['invoice_no'] . "\n";
    $receipt .= "Date: " . date('Y-m-d H:i:s', strtotime($sale['created_at'])) . "\n";
    $receipt .= "Cashier: " . ($sale['cashier'] ?? 'N/A') . "\n";
    $receipt .= "Customer: " . ($sale['customer_display_name'] ?? $sale['customer_name'] ?? 'Walk-in') . "\n";
    $receipt .= "------------------------\n";
    $receipt .= "ITEM          QTY   PRICE   TOTAL\n";
    $receipt .= "------------------------\n";
    foreach ($sale['items'] as $item) {
        $name = substr($item['product_name'], 0, 14);
        $receipt .= sprintf("%-14s %3d  %6s  %7s\n", 
            $name, 
            $item['quantity'], 
            $currency . number_format($item['price'], 2), 
            $currency . number_format($item['total'], 2)
        );
    }
    $receipt .= "------------------------\n";
    $receipt .= sprintf("%-28s %s\n", "Subtotal:", $currency . number_format($sale['subtotal'], 2));
    if ($sale['discount'] > 0) {
        $receipt .= sprintf("%-28s %s\n", "Discount:", "-" . $currency . number_format($sale['discount'], 2));
    }
    if ($sale['tax'] > 0) {
        $receipt .= sprintf("%-28s %s\n", "Tax:", $currency . number_format($sale['tax'], 2));
    }
    $receipt .= "========================\n";
    $receipt .= sprintf("%-28s %s\n", "TOTAL:", $currency . number_format($sale['total'], 2));
    $receipt .= "========================\n";
    $receipt .= "    " . $footer . "\n";
    $receipt .= "========================\n";
    return $receipt;
}
function getStockMovementReport($productId, $fromDate, $toDate) {
    $db = Database::getInstance()->getConnection();
    $deviceId = getCurrentDeviceId();

    $productId = (int)$productId;
    if ($productId <= 0) {
        return ['success' => false, 'message' => 'Invalid product.'];
    }

    $fromDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate) ? $fromDate : date('Y-m-01');
    $toDate   = preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate) ? $toDate : date('Y-m-d');

    if ($fromDate > $toDate) {
        return ['success' => false, 'message' => 'From date cannot be after To date.'];
    }

    // Current product on the currently selected device.
    $stmt = $db->prepare("
        SELECT id, device_id, name, barcode, barcode2, barcode3,
               alameen_code, alameen_number, coded_code, stock, unit1
        FROM products
        WHERE id = ? AND device_id = ?
        LIMIT 1
    ");
    $stmt->execute([$productId, $deviceId]);
    $product = $stmt->fetch();

    if (!$product) {
        return ['success' => false, 'message' => 'Product not found.'];
    }

    /*
     * All known stock movements are normalized to:
     * movement_date, movement_type, reference_no, reference_id,
     * qty_in, qty_out, user_name, notes
     *
     * Purchase date uses purchase_orders.created_at because the current
     * schema has no separate "received_at" field.
     *
     * Transfer IN is special: createTransfer() can create a new product
     * row on the destination device. We therefore match the destination
     * product to the source product by barcode/alameen/coded code/name.
     */
    $eventsSql = "
        SELECT
            s.created_at AS movement_date,
            'sale' AS movement_type,
            s.invoice_no AS reference_no,
            s.id AS reference_id,
            0 AS qty_in,
            si.quantity AS qty_out,
            u.name AS user_name,
            CONCAT('Sale ', s.invoice_no) AS notes
        FROM sale_items si
        INNER JOIN sales s ON s.id = si.sale_id
        LEFT JOIN users u ON u.id = s.user_id
        WHERE si.product_id = ?
          AND s.device_id = ?
          AND s.created_at >= ? AND s.created_at < DATE_ADD(?, INTERVAL 1 DAY)

        UNION ALL

        SELECT
            po.created_at AS movement_date,
            'purchase' AS movement_type,
            po.po_no AS reference_no,
            po.id AS reference_id,
            poi.quantity AS qty_in,
            0 AS qty_out,
            u.name AS user_name,
            CONCAT('Purchase Order ', po.po_no) AS notes
        FROM purchase_order_items poi
        INNER JOIN purchase_orders po ON po.id = poi.po_id
        LEFT JOIN users u ON u.id = po.user_id
        WHERE poi.product_id = ?
          AND po.device_id = ?
          AND po.status = 'received'
          AND po.created_at >= ? AND po.created_at < DATE_ADD(?, INTERVAL 1 DAY)

        UNION ALL

        SELECT
            r.return_date AS movement_date,
            'return' AS movement_type,
            r.return_no AS reference_no,
            r.id AS reference_id,
            ri.quantity AS qty_in,
            0 AS qty_out,
            u.name AS user_name,
            CONCAT('Return ', r.return_no) AS notes
        FROM return_items ri
        INNER JOIN returns r ON r.id = ri.return_id
        LEFT JOIN users u ON u.id = r.user_id
        WHERE ri.product_id = ?
          AND r.device_id = ?
          AND r.return_date >= ? AND r.return_date < DATE_ADD(?, INTERVAL 1 DAY)

        UNION ALL

        SELECT
            t.transfer_date AS movement_date,
            'transfer_out' AS movement_type,
            t.transfer_no AS reference_no,
            t.id AS reference_id,
            0 AS qty_in,
            ti.quantity AS qty_out,
            u.name AS user_name,
            CONCAT('Transfer to ', d.device_name) AS notes
        FROM transfer_items ti
        INNER JOIN transfers t ON t.id = ti.transfer_id
        LEFT JOIN users u ON u.id = t.user_id
        LEFT JOIN devices d ON d.id = t.to_device_id
        WHERE ti.product_id = ?
          AND t.from_device_id = ?
          AND t.status = 'completed'
          AND t.transfer_date >= ? AND t.transfer_date < DATE_ADD(?, INTERVAL 1 DAY)

        UNION ALL

        SELECT
            t.transfer_date AS movement_date,
            'transfer_in' AS movement_type,
            t.transfer_no AS reference_no,
            t.id AS reference_id,
            ti.quantity AS qty_in,
            0 AS qty_out,
            u.name AS user_name,
            CONCAT('Transfer from ', d.device_name) AS notes
        FROM transfer_items ti
        INNER JOIN transfers t ON t.id = ti.transfer_id
        INNER JOIN products sp ON sp.id = ti.product_id
        LEFT JOIN users u ON u.id = t.user_id
        LEFT JOIN devices d ON d.id = t.from_device_id
        WHERE t.to_device_id = ?
          AND t.status = 'completed'
          AND t.transfer_date >= ? AND t.transfer_date < DATE_ADD(?, INTERVAL 1 DAY)
          AND (
                (NULLIF(?, '') IS NOT NULL AND sp.barcode = ?)
             OR (NULLIF(?, '') IS NOT NULL AND sp.barcode2 = ?)
             OR (NULLIF(?, '') IS NOT NULL AND sp.barcode3 = ?)
             OR (NULLIF(?, '') IS NOT NULL AND sp.alameen_code = ?)
             OR (NULLIF(?, '') IS NOT NULL AND sp.alameen_number = ?)
             OR (NULLIF(?, '') IS NOT NULL AND sp.coded_code = ?)
             OR (
                    COALESCE(?, '') <> ''
                    AND sp.name = ?
                )
          )
        ORDER BY movement_date ASC, reference_id ASC
    ";

    $params = [
        // sales
        $productId, $deviceId, $fromDate, $toDate,

        // purchases
        $productId, $deviceId, $fromDate, $toDate,

        // returns
        $productId, $deviceId, $fromDate, $toDate,

        // transfer out
        $productId, $deviceId, $fromDate, $toDate,

        // transfer in
        $deviceId, $fromDate, $toDate,
        $product['barcode'], $product['barcode'],
        $product['barcode2'], $product['barcode2'],
        $product['barcode3'], $product['barcode3'],
        $product['alameen_code'], $product['alameen_code'],
        $product['alameen_number'], $product['alameen_number'],
        $product['coded_code'], $product['coded_code'],
        $product['name'], $product['name']
    ];

    $stmt = $db->prepare($eventsSql);
    $stmt->execute($params);
    $events = $stmt->fetchAll();

    /*
     * Reconciliation:
     * We know today's/current stock. Calculate the balance immediately
     * after the requested end date by reversing all known movements after
     * the requested period. Then calculate the opening balance by reversing
     * movements inside the requested period.
     *
     * This makes the report useful even though old manual adjustments and
     * direct stock edits were not historically logged.
     */
    $netAfterEndSql = "
        SELECT COALESCE(SUM(q.net_qty), 0) AS net_qty
        FROM (
            SELECT -SUM(si.quantity) AS net_qty
            FROM sale_items si
            INNER JOIN sales s ON s.id = si.sale_id
            WHERE si.product_id = ? AND s.device_id = ?
              AND s.created_at >= DATE_ADD(?, INTERVAL 1 DAY)

            UNION ALL

            SELECT SUM(poi.quantity) AS net_qty
            FROM purchase_order_items poi
            INNER JOIN purchase_orders po ON po.id = poi.po_id
            WHERE poi.product_id = ? AND po.device_id = ?
              AND po.status = 'received'
              AND po.created_at >= DATE_ADD(?, INTERVAL 1 DAY)

            UNION ALL

            SELECT SUM(ri.quantity) AS net_qty
            FROM return_items ri
            INNER JOIN returns r ON r.id = ri.return_id
            WHERE ri.product_id = ? AND r.device_id = ?
              AND r.return_date >= DATE_ADD(?, INTERVAL 1 DAY)

            UNION ALL

            SELECT -SUM(ti.quantity) AS net_qty
            FROM transfer_items ti
            INNER JOIN transfers t ON t.id = ti.transfer_id
            WHERE ti.product_id = ? AND t.from_device_id = ?
              AND t.status = 'completed'
              AND t.transfer_date >= DATE_ADD(?, INTERVAL 1 DAY)
        ) q
    ";

    // Transfer-in after the period is intentionally excluded from the
    // reconciliation because destination product IDs may have been cloned.
    // It is safer to avoid inventing a historical link here.
    $stmt = $db->prepare($netAfterEndSql);
    $stmt->execute([
        $productId, $deviceId, $toDate,
        $productId, $deviceId, $toDate,
        $productId, $deviceId, $toDate,
        $productId, $deviceId, $toDate
    ]);
    $netAfterEnd = (int)($stmt->fetch()['net_qty'] ?? 0);

    $currentStock = (int)$product['stock'];
    $closingBalance = $currentStock - $netAfterEnd;

    $periodNet = 0;
    foreach ($events as $event) {
        $periodNet += (int)$event['qty_in'] - (int)$event['qty_out'];
    }

    $openingBalance = $closingBalance - $periodNet;

    $balance = $openingBalance;
    $totalIn = 0;
    $totalOut = 0;

    foreach ($events as &$event) {
        $in = (int)$event['qty_in'];
        $out = (int)$event['qty_out'];
        $balance += $in - $out;

        $event['qty_in'] = $in;
        $event['qty_out'] = $out;
        $event['balance'] = $balance;

        $totalIn += $in;
        $totalOut += $out;
    }
    unset($event);

    return [
        'success' => true,
        'product' => $product,
        'from_date' => $fromDate,
        'to_date' => $toDate,
        'opening_balance' => $openingBalance,
        'total_in' => $totalIn,
        'total_out' => $totalOut,
        'closing_balance' => $closingBalance,
        'current_stock' => $currentStock,
        'events' => $events,
        'reconciles_with_current_stock' => ($closingBalance === $currentStock)
    ];
}
