<?php
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';
require_admin();

$pdo = getPDO();
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE statistics SET label_ar=?, label_en=?, value=?, icon=?, display_order=? WHERE id=?");
            $stmt->execute([$_POST['label_ar'], $_POST['label_en'], $_POST['value'], $_POST['icon'], $_POST['display_order'], $_POST['id']]);
            $success = "تم التحديث بنجاح";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$statistics = $pdo->query("SELECT * FROM statistics ORDER BY display_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الإحصائيات - بوابة خبرة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #066755, #044a3d); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_new.php">
                <i class="fas fa-arrow-right me-2"></i>العودة
            </a>
            <h4 class="text-white mb-0"><i class="fas fa-chart-bar me-2"></i>إدارة الإحصائيات</h4>
        </div>
    </nav>
    
    <div class="container-fluid px-4">
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>القيمة</th>
                            <th>الترتيب</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistics as $stat): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($stat['label_ar']) ?></strong></td>
                            <td><span class="badge bg-success"><?= htmlspecialchars($stat['value']) ?></span></td>
                            <td><?= $stat['display_order'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick='editStat(<?= json_encode($stat) ?>)'>
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal تعديل -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الإحصائية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>العنوان بالعربي *</label>
                            <input type="text" name="label_ar" id="edit_label_ar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>العنوان بالإنجليزي</label>
                            <input type="text" name="label_en" id="edit_label_en" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>القيمة *</label>
                            <input type="text" name="value" id="edit_value" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>الأيقونة</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>الترتيب</label>
                            <input type="number" name="display_order" id="edit_display_order" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" name="update" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editStat(stat) {
            document.getElementById('edit_id').value = stat.id;
            document.getElementById('edit_label_ar').value = stat.label_ar;
            document.getElementById('edit_label_en').value = stat.label_en || '';
            document.getElementById('edit_value').value = stat.value;
            document.getElementById('edit_icon').value = stat.icon || '';
            document.getElementById('edit_display_order').value = stat.display_order;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</body>
</html>


