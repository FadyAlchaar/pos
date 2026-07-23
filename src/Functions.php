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

        foreach ($data['items'] as $item) {
            $itemDiscount = $item['discount'] ?? 0;
            $itemTotal = ($item['price'] * $item['quantity']) - $itemDiscount;
            $stmt = $db->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price, discount, total) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$saleId, $item['product_id'], $item['quantity'], $item['price'], $itemDiscount, $itemTotal]);
            updateStock($item['product_id'], $item['quantity']);
        }

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
// PRINTING FUNCTIONS
// ============================================
function printReceipt($saleId, $method = 'normal') {
    $sale = getSaleById($saleId);
    if (!$sale) {
        return ['success' => false, 'message' => 'Sale not found'];
    }

    // Get language and direction
    $lang = getCurrentLanguage();
    $isRtl = $lang === 'ar';
    
    // Load translations
    $translations = [];
    $langFile = __DIR__ . "/../lang/{$lang}.php";
    if (file_exists($langFile)) {
        $translations = include $langFile;
    }

    $t = function($key) use ($translations, $lang) {
        if (isset($translations[$key])) {
            return $translations[$key];
        }
        $enFile = __DIR__ . "/../lang/en.php";
        if (file_exists($enFile)) {
            $en = include $enFile;
            if (isset($en[$key])) {
                return $en[$key];
            }
        }
        return $key;
    };

    $settings = getSettings();
    $storeName = $settings['store_name'] ?? 'POS System';
    $storeAddress = $settings['store_address'] ?? '';
    $storePhone = $settings['store_phone'] ?? '';
    $currency = getCurrencySymbol();
    $footer = $settings['receipt_footer'] ?? 'Thank you for your business!';

    // Compact thermal layout
    $itemWidth = 18;
    $qtyWidth = 3;
    $priceWidth = 8;
    $totalWidth = 9;
    $totalCols = $itemWidth + $qtyWidth + $priceWidth + $totalWidth + 5;

    // Box-drawing characters
    $topBorder = "┌" . str_repeat("─", $totalCols - 2) . "┐\n";
    $bottomBorder = "└" . str_repeat("─", $totalCols - 2) . "┘\n";
    $line = "├" . str_repeat("─", $totalCols - 2) . "┤\n";
    $doubleLine = "╞" . str_repeat("═", $totalCols - 2) . "╡\n";

    // Helper to pad text for RTL (right-aligned)
    $pad = function($text, $width) use ($isRtl) {
        $text = mb_substr($text, 0, $width);
        if ($isRtl) {
            return str_pad($text, $width, " ", STR_PAD_LEFT);
        }
        return str_pad($text, $width, " ", STR_PAD_RIGHT);
    };

    $receipt = "";

    // ===== HEADER =====
    $receipt .= $topBorder;
    $receipt .= "│ " . $pad(strtoupper($storeName), $totalCols - 4) . " │\n";
    $receipt .= "├" . str_repeat("─", $totalCols - 2) . "┤\n";
    if ($storeAddress) {
        $receipt .= "│ " . $pad($storeAddress, $totalCols - 4) . " │\n";
    }
    if ($storePhone) {
        $receipt .= "│ " . $pad("Tel: " . $storePhone, $totalCols - 4) . " │\n";
    }
    $receipt .= $line;

    // ===== INVOICE INFO (Right-aligned in RTL) =====
    $infoWidth = 14;
    $valueWidth = 22;
    $infoTotal = $infoWidth + $valueWidth + 3; // +3 for "│ " and " │"

    if ($isRtl) {
        // Arabic: label on the right, value on the left
        $receipt .= sprintf("│ %-{$valueWidth}s %-{$infoWidth}s │\n", $sale['invoice_no'], $t('invoice') . ":");
        $receipt .= sprintf("│ %-{$valueWidth}s %-{$infoWidth}s │\n", date('Y-m-d H:i:s', strtotime($sale['created_at'])), $t('date') . ":");
        $receipt .= sprintf("│ %-{$valueWidth}s %-{$infoWidth}s │\n", $sale['cashier'] ?? 'N/A', $t('cashier') . ":");
        $receipt .= sprintf("│ %-{$valueWidth}s %-{$infoWidth}s │\n", $sale['customer_name'] ?? 'Walk-in', $t('customer') . ":");
    } else {
        // English: label on the left, value on the right
        $receipt .= sprintf("│ %-{$infoWidth}s: %-{$valueWidth}s │\n", $t('invoice'), $sale['invoice_no']);
        $receipt .= sprintf("│ %-{$infoWidth}s: %-{$valueWidth}s │\n", $t('date'), date('Y-m-d H:i:s', strtotime($sale['created_at'])));
        $receipt .= sprintf("│ %-{$infoWidth}s: %-{$valueWidth}s │\n", $t('cashier'), $sale['cashier'] ?? 'N/A');
        $receipt .= sprintf("│ %-{$infoWidth}s: %-{$valueWidth}s │\n", $t('customer'), $sale['customer_name'] ?? 'Walk-in');
    }
    $receipt .= $line;

    // ===== TABLE HEADER =====
    if ($isRtl) {
        // Arabic: Total ← Price ← Qty ← Item (right-aligned)
        $receipt .= sprintf(
            "│ %{$totalWidth}s │ %{$priceWidth}s │ %{$qtyWidth}s │ %-{$itemWidth}s │\n",
            $t('total'), $t('price'), $t('qty'), $t('item')
        );
    } else {
        // English: Item → Qty → Price → Total (left-aligned)
        $receipt .= sprintf(
            "│ %-{$itemWidth}s │ %{$qtyWidth}s │ %{$priceWidth}s │ %{$totalWidth}s │\n",
            $t('item'), $t('qty'), $t('price'), $t('total')
        );
    }
    $receipt .= $line;

    // ===== TABLE ROWS =====
    foreach ($sale['items'] as $item) {
        $name = mb_substr($item['product_name'], 0, $itemWidth);
        $qty = $item['quantity'];
        $price = number_format($item['price'], 2);
        $total = number_format($item['total'], 2);

        if ($isRtl) {
            // Arabic: Total ← Price ← Qty ← Item
            $receipt .= sprintf(
                "│ %{$totalWidth}s │ %{$priceWidth}s │ %{$qtyWidth}s │ %-{$itemWidth}s │\n",
                $currency . $total, $currency . $price, $qty, $name
            );
        } else {
            // English: Item → Qty → Price → Total
            $receipt .= sprintf(
                "│ %-{$itemWidth}s │ %{$qtyWidth}s │ %{$priceWidth}s │ %{$totalWidth}s │\n",
                $name, $qty, $currency . $price, $currency . $total
            );
        }
    }

    $receipt .= $line;

    // ===== TOTALS =====
    $labelWidth = 24;
    $valueWidth2 = 14;
    if ($isRtl) {
        // Arabic: value on left, label on right
        $receipt .= sprintf("│ %-{$valueWidth2}s %-{$labelWidth}s │\n", $currency . number_format($sale['subtotal'], 2), $t('subtotal') . ":");
        if ($sale['discount'] > 0) {
            $receipt .= sprintf("│ %-{$valueWidth2}s %-{$labelWidth}s │\n", "-" . $currency . number_format($sale['discount'], 2), $t('discount') . ":");
        }
        if ($sale['tax'] > 0) {
            $receipt .= sprintf("│ %-{$valueWidth2}s %-{$labelWidth}s │\n", $currency . number_format($sale['tax'], 2), $t('tax') . ":");
        }
        $receipt .= $doubleLine;
        $receipt .= sprintf("│ %-{$valueWidth2}s %-{$labelWidth}s │\n", $currency . number_format($sale['total'], 2), $t('total') . ":");
    } else {
        // English: label on left, value on right
        $receipt .= sprintf("│ %-{$labelWidth}s: %{$valueWidth2}s │\n", $t('subtotal'), $currency . number_format($sale['subtotal'], 2));
        if ($sale['discount'] > 0) {
            $receipt .= sprintf("│ %-{$labelWidth}s: %{$valueWidth2}s │\n", $t('discount'), "-" . $currency . number_format($sale['discount'], 2));
        }
        if ($sale['tax'] > 0) {
            $receipt .= sprintf("│ %-{$labelWidth}s: %{$valueWidth2}s │\n", $t('tax'), $currency . number_format($sale['tax'], 2));
        }
        $receipt .= $doubleLine;
        $receipt .= sprintf("│ %-{$labelWidth}s: %{$valueWidth2}s │\n", $t('total'), $currency . number_format($sale['total'], 2));
    }

    // ===== FOOTER =====
    $receipt .= $bottomBorder;
    $receipt .= "│ " . $pad($footer, $totalCols - 4) . " │\n";
    $receipt .= $bottomBorder;
    $receipt .= "\x0A\x0A";

    // ============================================
    // PRINTING LOGIC (Bridge/Socket)
    // ============================================
    if ($method === 'normal') {
        return ['success' => true, 'receipt' => $receipt, 'sale' => $sale];
    }

    $bridgePath = $settings['printer_bridge_path'] ?? 'C:\\POS\\TextPrinter.exe';
    $printerName = $settings['printer_name'] ?? 'POS-58';

    if (file_exists($bridgePath)) {
        $tempFile = sys_get_temp_dir() . '/receipt_' . $saleId . '.txt';
        file_put_contents($tempFile, $receipt);
        $cmd = sprintf('"%s" "%s" "%s"', $bridgePath, $printerName, $tempFile);
        exec($cmd . ' 2>&1', $output, $returnCode);
        unlink($tempFile);
        if ($returnCode === 0) {
            return ['success' => true, 'message' => 'Printed successfully!'];
        } else {
            return ['success' => false, 'message' => 'Bridge error: ' . implode("\n", $output)];
        }
    }

    return ['success' => false, 'message' => 'No printing method available.'];
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
            LEFT JOIN users u ON e.user_id = u.id";
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
                          LEFT JOIN users u ON e.user_id = u.id 
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
    $stmt = $db->prepare("INSERT INTO expenses (device_id, category, amount, description, expense_date, user_id, payment_method) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $deviceId,
        $data['category'],
        $data['amount'],
        $data['description'] ?? null,
        $data['expense_date'],
        $data['user_id'],
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
        
        $stmt = $db->prepare("INSERT INTO returns 
            (sale_id, return_no, return_type, customer_name, customer_phone, user_id, reason, refund_method, total_refund, notes, device_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['sale_id'] ?? null,
            $returnNo,
            $returnType,
            $data['customer_name'] ?? null,
            $data['customer_phone'] ?? null,
            $data['user_id'],
            $data['reason'] ?? null,
            $data['refund_method'] ?? 'cash',
            $totalRefund,
            $data['notes'] ?? null,
            $deviceId
        ]);
        
        $returnId = $db->lastInsertId();
        
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
    $stmt = $db->prepare("SELECT t.*, 
                          d1.device_name as from_device, 
                          d2.device_name as to_device,
                          u.name as user_name
                          FROM transfers t
                          LEFT JOIN devices d1 ON t.from_device_id = d1.id
                          LEFT JOIN devices d2 ON t.to_device_id = d2.id
                          LEFT JOIN users u ON t.user_id = u.id
                          WHERE t.from_device_id = ? OR t.to_device_id = ?
                          ORDER BY t.id DESC LIMIT ?");
    $stmt->execute([$deviceId, $deviceId, $limit]);
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
function getWindowsPrinters() {
    // Run the Windows command to list printers
    $output = [];
    exec('wmic printer get name', $output);
    $printers = [];
    foreach ($output as $line) {
        $line = trim($line);
        if (!empty($line) && strpos($line, 'Name') === false) {
            $printers[] = $line;
        }
    }
    return $printers;
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