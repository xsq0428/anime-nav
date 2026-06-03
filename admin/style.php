<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();

$pdo = getDbConnection();

// 处理保存请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['frontend_style'])) {
    try {
        $stmt = $pdo->prepare("UPDATE settings SET setting_value=? WHERE setting_key='frontend_style'");
        $stmt->execute([$_POST['frontend_style']]);
        setFlashMessage('success', '前端风格切换成功！');
        redirect('style.php');
    } catch (PDOException $e) {
        setFlashMessage('danger', '保存失败：' . $e->getMessage());
    }
}

// 查询当前风格
$stmt = $pdo->query("SELECT setting_value FROM settings WHERE setting_key='frontend_style'");
$currentStyle = $stmt->fetchColumn() ?: 'default';

ob_start();
?>
<div class="mb-4">
    <h2><i class="bi bi-palette"></i> 前端界面风格</h2>
    <p class="text-muted">选择并预览不同的前端风格，切换后立即生效</p>
</div>

<form method="POST" action="">
    <input type="hidden" name="frontend_style" id="frontendStyleInput" value="">
    
    <div class="row">
        <!-- 默认二次元风 -->
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="card h-100 style-card" onclick="selectStyle('default')" style="cursor:pointer;">
                <div class="card-body text-center p-3" style="background: linear-gradient(135deg, #ffeef8, #f0e6ff);">
                    <div style="font-size:32px; margin-bottom:8px;">🌸</div>
                    <h6 class="mb-2">默认二次元风</h6>
                    <p class="text-muted mb-2 small">粉色渐变 · 可爱</p>
                    <?php if ($currentStyle === 'default'): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> 当前使用</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- 现代简约风 -->
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="card h-100 style-card" onclick="selectStyle('style1-modern')" style="cursor:pointer;">
                <div class="card-body text-center p-3" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color:white;">
                    <div style="font-size:32px; margin-bottom:8px;">🎯</div>
                    <h6 class="mb-2">现代简约风</h6>
                    <p class="mb-2 small" style="opacity:0.9;">紫蓝渐变 · 简洁</p>
                    <?php if ($currentStyle === 'style1-modern'): ?>
                    <span class="badge bg-light text-dark"><i class="bi bi-check-circle"></i> 当前使用</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- 毛玻璃拟态风 -->
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="card h-100 style-card" onclick="selectStyle('style2-glassmorphism')" style="cursor:pointer;">
                <div class="card-body text-center p-3" style="background: linear-gradient(135deg, #00c6fb, #005bea); color:white;">
                    <div style="font-size:32px; margin-bottom:8px;">💎</div>
                    <h6 class="mb-2">毛玻璃拟态风</h6>
                    <p class="mb-2 small" style="opacity:0.9;">通透 · 轻盈</p>
                    <?php if ($currentStyle === 'style2-glassmorphism'): ?>
                    <span class="badge bg-light text-dark"><i class="bi bi-check-circle"></i> 当前使用</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- 暗黑极客风 -->
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="card h-100 style-card" onclick="selectStyle('style3-dark')" style="cursor:pointer;">
                <div class="card-body text-center p-3" style="background: #0f0f0f; color:#00ff88;">
                    <div style="font-size:32px; margin-bottom:8px;">⚡</div>
                    <h6 class="mb-2">暗黑极客风</h6>
                    <p class="mb-2 small" style="color:#b0b0b0;">深色 · 极客范</p>
                    <?php if ($currentStyle === 'style3-dark'): ?>
                    <span class="badge bg-light text-dark"><i class="bi bi-check-circle"></i> 当前使用</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary" id="saveBtn" disabled>
            <i class="bi bi-check-circle"></i> 确认切换
        </button>
    </div>
</form>

<div class="card mt-4">
    <div class="card-header py-2">
        <i class="bi bi-info-circle"></i> 使用说明
    </div>
    <div class="card-body py-3">
        <ul class="mb-0 small">
            <li>点击上方任意风格卡片即可选择</li>
            <li>点击"确认切换"按钮保存选择</li>
            <li>切换后前台页面会立即应用新风格</li>
        </ul>
    </div>
</div>

<style>
.style-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.style-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.12);
}

.style-card.selected {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
</style>

<script>
function selectStyle(style) {
    // 移除所有卡片的选中状态
    document.querySelectorAll('.style-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // 添加选中卡片的样式
    event.currentTarget.classList.add('selected');
    
    // 设置隐藏 input 的值
    document.getElementById('frontendStyleInput').value = style;
    
    // 启用保存按钮
    document.getElementById('saveBtn').disabled = false;
}
</script>

<?php
$content = ob_get_clean();
require 'layout.php';
?>
