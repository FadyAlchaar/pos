<?php
// src/Auth.php
require_once __DIR__ . '/Database.php';

session_start();

// ============ Existing Functions (Keep) ============
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function isManager() {
    return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'manager']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?route=login');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ?route=dashboard');
        exit;
    }
}
// ============ Login Function ============
function login($username, $password) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['device_id'] = $user['device_id']; // <-- ADD THIS
        return true;
    }
    // After setting session variables, add:
    if (!empty($user['preferred_language'])) {
        setLanguage($user['preferred_language']);
    } else {
        // Fallback to default
        setLanguage('en');
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: index.php?route=login');
    exit;
}

// ============ NEW: Permission System ============
function isRole($role) {
    return ($_SESSION['user_role'] ?? '') === $role;
}

function hasPermission($permission) {
    $permissionsConfig = require __DIR__ . '/../config/permissions.php';
    $userRole = $_SESSION['user_role'] ?? 'cashier';
    
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    if (!isset($permissionsConfig['permissions'][$permission])) {
        return false;
    }
    
    $allowedRoles = $permissionsConfig['permissions'][$permission];
    return in_array($userRole, $allowedRoles);
}

function requirePermission($permission) {
    if (!hasPermission($permission)) {
        error_log("Permission denied: User {$_SESSION['user_name']} tried to access {$permission}");
        if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Permission denied.']);
            exit;
        } else {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
}

function getRoleHierarchy() {
    $permissionsConfig = require __DIR__ . '/../config/permissions.php';
    return $permissionsConfig['roles'];
}