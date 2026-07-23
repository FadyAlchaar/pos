<?php
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Auth.php';

$db = Database::getInstance()->getConnection();

// Delete existing admin
$stmt = $db->prepare("DELETE FROM users WHERE email = ?");
$stmt->execute(['admin@admin.com']);

// Insert new admin with password 'admin123'
$password = password_hash('admin123', PASSWORD_BCRYPT);
$stmt = $db->prepare("INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)");
$result = $stmt->execute(['Administrator', 'admin@admin.com', $password, 'admin', 1]);

if ($result) {
    echo "✅ Admin user created successfully!<br>";
    echo "Email: admin@admin.com<br>";
    echo "Password: admin123<br>";
    echo "User ID: " . $db->lastInsertId();
} else {
    echo "❌ Failed to create admin user.";
}