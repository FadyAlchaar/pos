<?php
requireAdmin();
$backupFile = null;
$message = '';
$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'run') {
    try {
        $db = Database::getInstance()->getConnection();
        $backupDir = __DIR__ . '/../backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $backupFile = $backupDir . '/backup_' . date('Y-m-d_H-i-s') . '.sql';

        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $output = "-- POS Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        foreach ($tables as $table) {
            $output .= "-- Table: $table\n";
            $stmt = $db->query("SELECT * FROM $table");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) continue;
            $columns = array_keys($rows[0]);
            $output .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";
            $values = [];
            foreach ($rows as $row) {
                $escaped = array_map(function($v) use ($db) {
                    return $v === null ? 'NULL' : $db->quote($v);
                }, $row);
                $values[] = "(" . implode(', ', $escaped) . ")";
            }
            $output .= implode(",\n", $values) . ";\n\n";
        }
        file_put_contents($backupFile, $output);
        $message = '✅ Backup created successfully!';
    } catch (Exception $e) {
        $error = '❌ Backup failed: ' . $e->getMessage();
    }
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight: 700; color: var(--dark);"><?= __('backup') ?></h4>
</div>

<div class="card fade-in">
    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <p><?= __('backup_description') ?></p>
        <form method="GET" action="?route=backup">
            <input type="hidden" name="route" value="backup">
            <input type="hidden" name="action" value="run">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-database"></i> <?= __('run_backup') ?>
            </button>
        </form>

        <?php if ($backupFile && file_exists($backupFile)): ?>
            <div class="mt-3">
                <p><strong><?= __('backup_file') ?>:</strong> <?= basename($backupFile) ?></p>
                <a href="<?= str_replace($_SERVER['DOCUMENT_ROOT'], '', $backupFile) ?>" class="btn btn-success" download>
                    <i class="fas fa-download"></i> <?= __('download_backup') ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <h5><?= __('backup_instructions') ?></h5>
            <ul>
                <li><?= __('backup_instructions_1') ?></li>
                <li><?= __('backup_instructions_2') ?></li>
            </ul>
        </div>
    </div>
</div>