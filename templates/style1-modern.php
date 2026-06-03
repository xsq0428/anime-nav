<?php
// 风格 1: 现代简约风格
require_once 'config/database.php';

$pdo = getDbConnection();

$stmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$stmt = $pdo->query("SELECT * FROM urls WHERE is_active = 1 ORDER BY sort_order");
$urls = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM ads WHERE is_active = 1 ORDER BY sort_order");
$ads = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM announcements WHERE is_active = 1 ORDER BY sort_order");
$announcements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'] ?? '二次元地址发布页') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans SC', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: var(--text);
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .header {
            text-align: center;
            padding: 20px 16px;
            margin-bottom: 20px;
        }

        .logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.2);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-light);
        }

        .announcement-list {
            margin-bottom: 20px;
        }

        .announcement-item {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 8px 12px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .url-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .url-card {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 10px 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .url-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
        }

        .url-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .url-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .ad-section {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 12px;
            margin: 12px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .ad-section h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
            text-align: center;
        }

        .ad-item {
            padding: 8px 12px;
            background: rgba(0,0,0,0.03);
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .ad-item:last-child {
            margin-bottom: 0;
        }

        .ad-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
            font-size: 13px;
        }

        .ad-content {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ad-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
        }

        .ad-link:hover {
            text-decoration: underline;
        }

        .copy-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: var(--secondary);
            transform: scale(1.05);
        }

        .group-link {
            display: block;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            text-align: center;
            padding: 14px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        }

        .group-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .address-section {
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            border-radius: 20px;
            padding: 16px;
            text-align: center;
            color: white;
        }

        .address-section h3 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .permanent-url {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 12px;
            font-family: monospace;
            color: white;
            display: inline-block;
            margin-bottom: 10px;
        }

        .address-tips {
            font-size: 12px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .footer {
            margin: 20px 16px 0;
            text-align: center;
            padding: 20px;
            color: var(--text-light);
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .container { max-width: 100%; padding: 20px 15px; }
        }
        
        @media (max-width: 480px) {
            .main-content { margin: 0 8px; padding: 20px; }
            .main-title { font-size: 18px; }
            .button-grid { grid-template-columns: 1fr; gap: 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="<?= htmlspecialchars($settings['site_logo'] ?? '') ?>" alt="Logo">
            </div>
            <h1 class="main-title"><?= htmlspecialchars($settings['site_title'] ?? '') ?></h1>
            <p class="subtitle"><?= htmlspecialchars($settings['site_subtitle'] ?? '') ?></p>
        </header>

        <?php if (!empty($announcements)): ?>
        <div class="announcement-list">
            <?php foreach ($announcements as $ann): ?>
            <div class="announcement-item">
                <span><?= htmlspecialchars($ann['icon']) ?></span>
                <span><?= htmlspecialchars($ann['content']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="url-grid">
            <?php foreach ($urls as $url): ?>
            <div class="url-card" onclick="trackClick(<?= $url['id'] ?>, '<?= htmlspecialchars($url['url']) ?>')">
                <div class="url-icon"><?= htmlspecialchars($url['icon']) ?></div>
                <div class="url-name"><?= htmlspecialchars($url['name']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($ads)): ?>
        <div class="ad-section">
            <h3>💻 推荐服务</h3>
            <?php foreach ($ads as $ad): ?>
            <div class="ad-item">
                <div class="ad-label"><?= htmlspecialchars($ad['title']) ?></div>
                <div class="ad-content">
                    <?php if ($ad['link_url']): ?>
                    <a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank" class="ad-link"><?= htmlspecialchars($ad['content']) ?></a>
                    <?php else: ?>
                    <span style="color: var(--text-light);"><?= htmlspecialchars($ad['content']) ?></span>
                    <?php endif; ?>
                    <?php if ($ad['copy_text']): ?>
                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($ad['copy_text']) ?>')">复制</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <a href="<?= htmlspecialchars($settings['group_link_url'] ?? '#') ?>" class="group-link" onclick="trackClick(0, '<?= htmlspecialchars($settings['group_link_url'] ?? '') ?>'); return false;">
            <span class="group-icon">👆</span>
            <?= htmlspecialchars($settings['group_link_text'] ?? '点击此处加内部群永不失联') ?>
        </a>

        <div class="address-section">
            <h3>🔗 收藏本站永久地址</h3>
            <div class="permanent-url"><?= htmlspecialchars($settings['permanent_url'] ?? '') ?></div>
            <p class="address-tips">
                网站域名经常更新，防止网站打不开<br>
                请务必截图收藏此网页，永久有效!
            </p>
        </div>

        <footer class="footer">
            <p><?= htmlspecialchars($settings['footer_text'] ?? '© 2024 . All Rights Reserved') ?></p>
        </footer>
    </div>

    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => showToast('✅ 复制成功！', '#28a745'));
            } else {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.top = "0";
                textArea.style.left = "0";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy') ? showToast('✅ 复制成功！', '#28a745') : showToast('❌ 复制失败，请手动复制', '#dc3545');
                } catch (err) {
                    showToast('❌ 复制失败，请手动复制', '#dc3545');
                }
                document.body.removeChild(textArea);
            }
        }

        function showToast(message, bgColor) {
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;top:20px;right:20px;background:' + bgColor + ';color:white;padding:12px 20px;border-radius:8px;font-size:14px;font-weight:600;z-index:10000;box-shadow:0 4px 12px rgba(0,0,0,0.3);';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(function() { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 3000);
        }

        function trackClick(urlId, url) {
            fetch('api/click.php?id=' + urlId, { method: 'POST' });
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
