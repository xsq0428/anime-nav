<?php
// 风格 3: 深色主题
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
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-card: #252525;
            --accent: #00ff88;
            --accent-secondary: #00ccff;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border: #333333;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans SC', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            padding: 30px;
            background: linear-gradient(135deg, var(--bg-secondary), var(--bg-card));
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 3px solid var(--accent);
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 15px;
            color: var(--text-secondary);
        }

        .announcement-list {
            margin-bottom: 15px;
        }

        .announcement-item {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border-left: 4px solid var(--accent);
        }

        .url-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .url-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .url-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 204, 255, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .url-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent);
            box-shadow: 0 8px 24px rgba(0, 255, 136, 0.2);
        }

        .url-card:hover::before {
            opacity: 1;
        }

        .url-content {
            position: relative;
            z-index: 1;
        }

        .url-icon {
            font-size: 24px;
            margin-bottom: 12px;
            display: block;
        }

        .url-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .ad-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 15px;
        }

        .ad-section h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--accent);
            text-align: center;
        }

        .ad-item {
            padding: 16px;
            background: var(--bg-card);
            border-radius: 12px;
            margin-bottom: 12px;
            border: 1px solid var(--border);
        }

        .ad-item:last-child {
            margin-bottom: 0;
        }

        .ad-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .ad-content-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ad-link {
            color: var(--accent-secondary);
            text-decoration: none;
            font-weight: 500;
        }

        .ad-link:hover {
            text-decoration: underline;
            color: var(--accent);
        }

        .copy-btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
            color: var(--bg-primary);
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0, 255, 136, 0.4);
        }

        .group-link {
            display: block;
            background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
            color: var(--bg-primary);
            text-align: center;
            padding: 10px 8px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(0, 255, 136, 0.3);
        }

        .group-link:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 32px rgba(0, 255, 136, 0.4);
        }

        .address-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
        }

        .address-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .permanent-url {
            background: var(--bg-card);
            padding: 14px 20px;
            border-radius: 10px;
            font-family: monospace;
            color: var(--accent);
            display: inline-block;
            margin-bottom: 12px;
            border: 1px solid var(--accent);
        }

        .address-tips {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding: 10px 8px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container { padding: 20px 16px; }
            .url-grid { grid-template-columns: 1fr; }
        }

        .glow-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 999;
            background: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.03), transparent 70%);
        }
    </style>
</head>
<body>
    <div class="glow-effect"></div>
    
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
                <div class="url-content">
                    <span class="url-icon"><?= htmlspecialchars($url['icon']) ?></span>
                    <span class="url-name"><?= htmlspecialchars($url['name']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($ads)): ?>
        <div class="ad-section">
            <h3>💻 推荐服务</h3>
            <?php foreach ($ads as $ad): ?>
            <div class="ad-item">
                <div class="ad-label"><?= htmlspecialchars($ad['title']) ?></div>
                <div class="ad-content-wrap">
                    <?php if ($ad['link_url']): ?>
                    <a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank" class="ad-link"><?= htmlspecialchars($ad['content']) ?></a>
                    <?php else: ?>
                    <span style="color: var(--text-secondary);"><?= htmlspecialchars($ad['content']) ?></span>
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
            toast.textContent = message;
            toast.style.cssText = 'position:fixed;top:20px;right:20px;background:' + bgColor + ';color:white;padding:12px 20px;border-radius:8px;font-size:14px;font-weight:600;z-index:10000;box-shadow:0 4px 12px rgba(0,0,0,0.3);';
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
