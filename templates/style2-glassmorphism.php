<?php
// 风格 2: 毛玻璃风格
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
            --primary: #00c6fb;
            --secondary: #005bea;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text: #ffffff;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans SC', sans-serif;
            background: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
            min-height: 100vh;
            color: var(--text);
            overflow-x: hidden;
        }

        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s infinite;
        }

        .bubble:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .bubble:nth-child(2) { width: 60px; height: 60px; top: 20%; right: 15%; animation-delay: 2s; }
        .bubble:nth-child(3) { width: 100px; height: 100px; bottom: 15%; left: 20%; animation-delay: 4s; }
        .bubble:nth-child(4) { width: 70px; height: 70px; bottom: 25%; right: 10%; animation-delay: 6s; }
        .bubble:nth-child(5) { width: 90px; height: 90px; top: 50%; left: 50%; animation-delay: 8s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.1); }
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px 0;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.2);
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
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .subtitle {
            font-size: 15px;
            opacity: 0.9;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .announcement-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .url-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        .url-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 10px 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .url-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .url-icon {
            font-size: 24px;
            margin-bottom: 10px;
            display: block;
        }

        .url-name {
            font-size: 14px;
            font-weight: 500;
        }

        .ad-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .ad-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }

        .ad-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .ad-label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .ad-content-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ad-link {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            opacity: 0.9;
        }

        .ad-link:hover {
            text-decoration: underline;
        }

        .copy-btn {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .group-btn {
            display: block;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            color: white;
            text-align: center;
            padding: 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 12px;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .group-btn:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .address-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 16px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .address-box h3 {
            font-size: 16px;
            margin-bottom: 12px;
        }

        .permanent-url {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 10px;
            font-family: monospace;
            display: inline-block;
            margin-bottom: 12px;
        }

        .address-tips {
            font-size: 13px;
            opacity: 0.8;
            line-height: 1.6;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            padding: 10px 8px;
            opacity: 0.8;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container { padding: 20px 16px; }
            .url-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="<?= htmlspecialchars($settings['site_logo'] ?? '') ?>" alt="Logo">
            </div>
            <h1 class="main-title"><?= htmlspecialchars($settings['site_title'] ?? '') ?></h1>
            <p class="subtitle"><?= htmlspecialchars($settings['site_subtitle'] ?? '') ?></p>
        </header>

        <?php if (!empty($announcements)): ?>
        <div class="glass-card">
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
            <button class="url-btn" onclick="trackClick(<?= $url['id'] ?>, '<?= htmlspecialchars($url['url']) ?>')">
                <span class="url-icon"><?= htmlspecialchars($url['icon']) ?></span>
                <span class="url-name"><?= htmlspecialchars($url['name']) ?></span>
            </button>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($ads)): ?>
        <div class="ad-section">
            <div class="ad-title">💻 推荐服务</div>
            <?php foreach ($ads as $ad): ?>
            <div class="ad-item">
                <div class="ad-label"><?= htmlspecialchars($ad['title']) ?></div>
                <div class="ad-content-wrap">
                    <?php if ($ad['link_url']): ?>
                    <a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank" class="ad-link"><?= htmlspecialchars($ad['content']) ?></a>
                    <?php else: ?>
                    <span style="opacity: 0.9;"><?= htmlspecialchars($ad['content']) ?></span>
                    <?php endif; ?>
                    <?php if ($ad['copy_text']): ?>
                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($ad['copy_text']) ?>')">复制</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <a href="<?= htmlspecialchars($settings['group_link_url'] ?? '#') ?>" class="group-btn" onclick="trackClick(0, '<?= htmlspecialchars($settings['group_link_url'] ?? '') ?>'); return false;">
            <span class="group-icon">👆</span>
            <?= htmlspecialchars($settings['group_link_text'] ?? '点击此处加内部群永不失联') ?>
        </a>

        <div class="address-box">
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
