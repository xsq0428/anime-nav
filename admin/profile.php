<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
requireLogin();

$pdo = getDbConnection();
$admin = getCurrentAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $newUsername = trim($_POST['username'] ?? '');
        
        if (empty($newUsername)) {
            $error = '用户名不能为空';
        } else {
            $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
            $stmt->execute([$newUsername, $admin['id']]);
            
            $_SESSION['admin_username'] = $newUsername;
            $success = '用户名修改成功';
            $admin['username'] = $newUsername;
        }
    }
    
    if ($action === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = '所有密码字段都不能为空';
        } elseif ($newPassword !== $confirmPassword) {
            $error = '两次输入的新密码不一致';
        } elseif (strlen($newPassword) < 6) {
            $error = '新密码长度不能少于 6 位';
        } else {
            $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
            $stmt->execute([$admin['id']]);
            $adminData = $stmt->fetch();
            
            if (password_verify($currentPassword, $adminData['password'])) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $updateStmt->execute([$hashedPassword, $admin['id']]);
                
                $success = '密码修改成功';
            } else {
                $error = '当前密码不正确';
            }
        }
    }
}

ob_start();
?>
<div class="page-header">
    <h2><i class="bi bi-person-circle"></i> 个人中心</h2>
    <p class="text-muted mb-0">修改账号信息和个人密码</p>
</div>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle me-2"></i>
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>
    <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> 修改用户名</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="mb-3">
                        <label class="form-label">当前用户名</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">新用户名</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> 修改用户名
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-shield-lock"></i> 修改密码</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="mb-3">
                        <label class="form-label">当前密码</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">新密码</label>
                        <input type="password" class="form-control" name="new_password" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">确认新密码</label>
                        <input type="password" class="form-control" name="confirm_password" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-key"></i> 修改密码
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle"></i> 账户信息</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td style="width: 150px;" class="text-muted">用户 ID</td>
                <td><?= $admin['id'] ?></td>
            </tr>
            <tr>
                <td class="text-muted">用户名</td>
                <td><?= htmlspecialchars($admin['username']) ?></td>
            </tr>
            <tr>
                <td class="text-muted">登录时间</td>
                <td>
                    <?php
                    $stmt = $pdo->prepare("SELECT last_login FROM admins WHERE id = ?");
                    $stmt->execute([$admin['id']]);
                    $lastLogin = $stmt->fetchColumn();
                    echo $lastLogin ? date('Y-m-d H:i:s', strtotime($lastLogin)) : '首次登录';
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
