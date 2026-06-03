<?php
require_once 'config/database.php';

$pdo = getDbConnection();

$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// 根据配置加载不同风格的模板
$frontendStyle = $settings['frontend_style'] ?? 'default';

switch ($frontendStyle) {
    case 'style1-modern':
        $templateFile = 'templates/style1-modern.php';
        break;
    case 'style2-glassmorphism':
        $templateFile = 'templates/style2-glassmorphism.php';
        break;
    case 'style3-dark':
        $templateFile = 'templates/style3-dark.php';
        break;
    default:
        $templateFile = 'templates/default.php';
        break;
}

// 如果模板文件存在则加载，否则使用默认
if (file_exists($templateFile)) {
    include $templateFile;
} else {
    include 'templates/default.php';
}
?>
