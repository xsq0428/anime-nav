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
            .url-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 480px) {
            .container { padding: 20px 16px; }
            .url-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* 公告弹窗样式 */
        .announcement-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .announcement-modal.show {
            display: flex;
            animation: fadeIn 0.2s ease-out;
        }

        .announcement-modal-content {
            background: linear-gradient(135deg, #f0f4ff, #f8f9fa);
            border-radius: 20px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.3);
            position: relative;
            border: 2px solid rgba(99, 102, 241, 0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .announcement-modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .announcement-modal-close:hover {
            transform: rotate(90deg);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.5);
        }

        .announcement-modal-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(99, 102, 241, 0.2);
        }

        .announcement-modal-icon {
            font-size: 32px;
        }

        .announcement-modal-title {
            font-size: 16px;
            font-weight: 700;
            color: #6366f1;
        }

        .announcement-modal-body {
            font-size: 14px;
            color: #1e293b;
            line-height: 1.8;
        }

        .announcement-modal-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(99, 102, 241, 0.2);
        }

        .popup-nav-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .popup-nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
        }

        .popup-nav-btn:active {
            transform: translateY(0);
        }

        .popup-nav-btn.close-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .popup-nav-btn.close-btn:hover {
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.6);
        }

        .popup-nav-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        .popup-dots {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .popup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .popup-dot.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            transform: scale(1.3);
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.5);
        }

        .popup-dot:hover {
            transform: scale(1.2);
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

        <!-- 公告弹窗 -->
        <?php
        $popupAnnouncements = array_filter($announcements, function($ann) {
            return $ann['is_popup'] == 1;
        });
        $popupAnnouncements = array_values($popupAnnouncements);
        if (!empty($popupAnnouncements)):
        ?>
        <div class="announcement-modal" id="announcementModal">
            <div class="announcement-modal-content">
                <button class="announcement-modal-close" onclick="closeAnnouncementModal()">×</button>
                <div class="announcement-modal-header">
                    <span class="announcement-modal-icon" id="modalIcon"></span>
                    <span class="announcement-modal-title">重要通知</span>
                </div>
                <div class="announcement-modal-body" id="modalBody"></div>
                <div class="announcement-modal-footer" id="modalFooter"></div>
            </div>
        </div>
        <?php endif; ?>

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

        // 弹窗公告数据
        const popupAnnouncements = <?= json_encode(array_values($popupAnnouncements), JSON_UNESCAPED_UNICODE) ?>;
        let currentPopupIndex = 0;

        function showPopupAnnouncement(index) {
            if (index >= popupAnnouncements.length || index < 0) return;
            
            const ann = popupAnnouncements[index];
            document.getElementById('modalIcon').textContent = ann.icon;
            document.getElementById('modalBody').innerHTML = ann.content.replace(/\n/g, '<br>');
            
            const isLast = index === popupAnnouncements.length - 1;
            const isFirst = index === 0;
            
            // 更新底部按钮和指示器
            const footer = document.getElementById('modalFooter');
            let html = '<div class="popup-dots">';
            for (let i = 0; i < popupAnnouncements.length; i++) {
                html += '<span class="popup-dot' + (i === index ? ' active' : '') + '" onclick="jumpToPopup(' + i + ')"></span>';
            }
            html += '</div>';
            
            if (popupAnnouncements.length > 1) {
                html += '<button class="popup-nav-btn' + (isFirst ? '" disabled' : '" onclick="prevPopup()') + '"><span>◀</span> 上一条</button>';
                html += '<button class="popup-nav-btn' + (isLast ? '" disabled' : '" onclick="nextPopup()') + '"><span>▶</span> 下一条</button>';
            } else {
                html += '<button class="popup-nav-btn close-btn" onclick="closeAnnouncementModal()">关闭</button>';
            }
            
            footer.innerHTML = html;
        }

        function prevPopup() {
            if (currentPopupIndex > 0) {
                currentPopupIndex--;
                showPopupAnnouncement(currentPopupIndex);
            }
        }

        function nextPopup() {
            if (currentPopupIndex < popupAnnouncements.length - 1) {
                currentPopupIndex++;
                showPopupAnnouncement(currentPopupIndex);
            }
        }

        function jumpToPopup(index) {
            currentPopupIndex = index;
            showPopupAnnouncement(currentPopupIndex);
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').classList.remove('show');
            sessionStorage.setItem('announcementClosed_' + window.location.hostname, 'true');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // 显示弹窗
            const modal = document.getElementById('announcementModal');
            if (modal && popupAnnouncements.length > 0 && sessionStorage.getItem('announcementClosed_' + window.location.hostname) !== 'true') {
                setTimeout(function() {
                    showPopupAnnouncement(0);
                    modal.classList.add('show');
                }, 500);
            }
        });
    </script>
</body>
</html>
