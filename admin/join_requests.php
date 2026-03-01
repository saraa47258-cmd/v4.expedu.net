<?php
/**
 * لوحة إدارة طلبات الانضمام
 */
require __DIR__ . '/../public/includes/functions.php';
require __DIR__ . '/../public/includes/auth.php';

// التحقق من صلاحيات الأدمن
require_admin();

$pdo = getPDO();
$me = current_user();

// معالجة تحديث حالة الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $requestId = (int)$_POST['request_id'];
    $newStatus = $_POST['status'];
    $notes = trim($_POST['notes'] ?? '');
    
    $stmt = $pdo->prepare("UPDATE join_requests SET status = ?, notes = ? WHERE id = ?");
    $stmt->execute([$newStatus, $notes, $requestId]);
    
    $successMessage = "تم تحديث حالة الطلب بنجاح";
}

// الفلترة
$filterType = $_GET['type'] ?? '';
$filterStatus = $_GET['status'] ?? '';

$sql = "SELECT * FROM join_requests WHERE 1=1";
$params = [];

if ($filterType) {
    $sql .= " AND request_type = ?";
    $params[] = $filterType;
}

if ($filterStatus) {
    $sql .= " AND status = ?";
    $params[] = $filterStatus;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();

// الإحصائيات
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
        SUM(CASE WHEN request_type = 'expert' THEN 1 ELSE 0 END) as experts,
        SUM(CASE WHEN request_type = 'supervisor' THEN 1 ELSE 0 END) as supervisors,
        SUM(CASE WHEN request_type = 'institution' THEN 1 ELSE 0 END) as institutions,
        SUM(CASE WHEN request_type = 'learner' THEN 1 ELSE 0 END) as learners
    FROM join_requests
")->fetch();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلبات الانضمام - ExpEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background: #f8f9fa; }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border-right: 4px solid #066755;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #066755;
        }
        
        .stat-label {
            color: #666;
            font-weight: 600;
        }
        
        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .request-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .request-card:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }
        
        .badge-type {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .badge-expert { background: #0077b6; color: white; }
        .badge-supervisor { background: #06d6a0; color: white; }
        .badge-institution { background: #ff6b6b; color: white; }
        .badge-learner { background: #4ecdc4; color: white; }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-contacted { background: #cfe2ff; color: #084298; }
        .status-approved { background: #d1e7dd; color: #0f5132; }
        .status-rejected { background: #f8d7da; color: #842029; }
        
        .btn-action {
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-0">
                    <i class="fas fa-users-cog me-2"></i>
                    إدارة طلبات الانضمام
                </h1>
            </div>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للوحة التحكم
            </a>
        </div>
        
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($successMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- الإحصائيات -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stat-number"><?= $stats['total'] ?></div>
                    <div class="stat-label">إجمالي الطلبات</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #ffc107;">
                    <div class="stat-number" style="color: #ffc107;"><?= $stats['pending'] ?></div>
                    <div class="stat-label">قيد الانتظار</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #0077b6;">
                    <div class="stat-number" style="color: #0077b6;"><?= $stats['contacted'] ?></div>
                    <div class="stat-label">تم التواصل</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #28a745;">
                    <div class="stat-number" style="color: #28a745;"><?= $stats['approved'] ?></div>
                    <div class="stat-label">تمت الموافقة</div>
                </div>
            </div>
        </div>
        
        <!-- أنواع الطلبات -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #0077b6;">
                    <div class="stat-number" style="color: #0077b6;"><?= $stats['experts'] ?></div>
                    <div class="stat-label">خبراء</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #06d6a0;">
                    <div class="stat-number" style="color: #06d6a0;"><?= $stats['supervisors'] ?></div>
                    <div class="stat-label">مشرفين</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #ff6b6b;">
                    <div class="stat-number" style="color: #ff6b6b;"><?= $stats['institutions'] ?></div>
                    <div class="stat-label">مؤسسات</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="border-right-color: #4ecdc4;">
                    <div class="stat-number" style="color: #4ecdc4;"><?= $stats['learners'] ?></div>
                    <div class="stat-label">متعلمين</div>
                </div>
            </div>
        </div>
        
        <!-- الفلترة -->
        <div class="filter-card">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">نوع الطلب</label>
                    <select name="type" class="form-select">
                        <option value="">الكل</option>
                        <option value="expert" <?= $filterType === 'expert' ? 'selected' : '' ?>>خبير</option>
                        <option value="supervisor" <?= $filterType === 'supervisor' ? 'selected' : '' ?>>مشرف</option>
                        <option value="institution" <?= $filterType === 'institution' ? 'selected' : '' ?>>مؤسسة</option>
                        <option value="learner" <?= $filterType === 'learner' ? 'selected' : '' ?>>متعلم</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>قيد الانتظار</option>
                        <option value="contacted" <?= $filterStatus === 'contacted' ? 'selected' : '' ?>>تم التواصل</option>
                        <option value="approved" <?= $filterStatus === 'approved' ? 'selected' : '' ?>>موافق عليه</option>
                        <option value="rejected" <?= $filterStatus === 'rejected' ? 'selected' : '' ?>>مرفوض</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>
                        تطبيق الفلتر
                    </button>
                </div>
            </form>
        </div>
        
        <!-- قائمة الطلبات -->
        <?php if (empty($requests)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-5x text-muted mb-3"></i>
                <h4>لا توجد طلبات</h4>
            </div>
        <?php else: ?>
            <?php foreach ($requests as $request): ?>
                <div class="request-card">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                $typeLabels = [
                                    'expert' => 'خبير',
                                    'supervisor' => 'مشرف',
                                    'institution' => 'مؤسسة',
                                    'learner' => 'متعلم'
                                ];
                                $typeClass = 'badge-' . $request['request_type'];
                                ?>
                                <span class="badge-type <?= $typeClass ?> me-2">
                                    <?= $typeLabels[$request['request_type']] ?>
                                </span>
                                
                                <?php
                                $statusLabels = [
                                    'pending' => 'قيد الانتظار',
                                    'contacted' => 'تم التواصل',
                                    'approved' => 'موافق عليه',
                                    'rejected' => 'مرفوض'
                                ];
                                $statusClass = 'status-' . $request['status'];
                                ?>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?= $statusLabels[$request['status']] ?>
                                </span>
                            </div>
                            
                            <h5 class="mb-2">
                                <i class="fas fa-user me-2"></i>
                                <?= htmlspecialchars($request['full_name']) ?>
                            </h5>
                            
                            <div class="mb-2">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                <?= htmlspecialchars($request['email']) ?>
                                <i class="fas fa-phone me-2 ms-3 text-muted"></i>
                                <?= htmlspecialchars($request['phone']) ?>
                            </div>
                            
                            <?php if ($request['request_type'] === 'expert'): ?>
                                <div class="mt-3">
                                    <strong>التخصص:</strong> <?= htmlspecialchars($request['specialization']) ?><br>
                                    <strong>الخبرة:</strong> <?= htmlspecialchars(substr($request['experience_description'], 0, 100)) ?>...
                                    <?php if ($request['cv_file_path']): ?>
                                        <br><strong>السيرة الذاتية:</strong> 
                                        <a href="<?= htmlspecialchars($request['cv_file_path']) ?>" target="_blank">
                                            <i class="fas fa-download"></i> تحميل
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($request['request_type'] === 'supervisor'): ?>
                                <div class="mt-3">
                                    <strong>مجال الإشراف:</strong> <?= htmlspecialchars($request['supervision_field']) ?>
                                </div>
                            <?php elseif ($request['request_type'] === 'institution'): ?>
                                <div class="mt-3">
                                    <strong>اسم المؤسسة:</strong> <?= htmlspecialchars($request['institution_name']) ?><br>
                                    <strong>النوع:</strong> <?= htmlspecialchars($request['institution_type']) ?>
                                </div>
                            <?php elseif ($request['request_type'] === 'learner'): ?>
                                <div class="mt-3">
                                    <strong>الفئة:</strong> <?= htmlspecialchars($request['learner_category']) ?><br>
                                    <strong>مجال التعلم:</strong> <?= htmlspecialchars($request['learning_field']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="text-muted mt-2">
                                <i class="fas fa-clock me-1"></i>
                                <?= date('Y-m-d H:i', strtotime($request['created_at'])) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <button class="btn btn-sm btn-primary w-100 mb-2" data-bs-toggle="modal" 
                                    data-bs-target="#updateModal<?= $request['id'] ?>">
                                <i class="fas fa-edit me-2"></i>
                                تحديث الحالة
                            </button>
                            <button class="btn btn-sm btn-info w-100" data-bs-toggle="modal" 
                                    data-bs-target="#detailsModal<?= $request['id'] ?>">
                                <i class="fas fa-info-circle me-2"></i>
                                عرض التفاصيل
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal: تحديث الحالة -->
                <div class="modal fade" id="updateModal<?= $request['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">تحديث حالة الطلب</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الحالة</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>قيد الانتظار</option>
                                            <option value="contacted" <?= $request['status'] === 'contacted' ? 'selected' : '' ?>>تم التواصل</option>
                                            <option value="approved" <?= $request['status'] === 'approved' ? 'selected' : '' ?>>موافق عليه</option>
                                            <option value="rejected" <?= $request['status'] === 'rejected' ? 'selected' : '' ?>>مرفوض</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ملاحظات</label>
                                        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($request['notes'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Modal: التفاصيل الكاملة -->
                <div class="modal fade" id="detailsModal<?= $request['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">تفاصيل الطلب</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">النوع</th>
                                        <td><?= $typeLabels[$request['request_type']] ?></td>
                                    </tr>
                                    <tr>
                                        <th>الاسم</th>
                                        <td><?= htmlspecialchars($request['full_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>البريد</th>
                                        <td><?= htmlspecialchars($request['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>الهاتف</th>
                                        <td><?= htmlspecialchars($request['phone']) ?></td>
                                    </tr>
                                    <?php if ($request['specialization']): ?>
                                    <tr>
                                        <th>التخصص</th>
                                        <td><?= htmlspecialchars($request['specialization']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($request['experience_description']): ?>
                                    <tr>
                                        <th>وصف الخبرة</th>
                                        <td><?= nl2br(htmlspecialchars($request['experience_description'])) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($request['institution_name']): ?>
                                    <tr>
                                        <th>اسم المؤسسة</th>
                                        <td><?= htmlspecialchars($request['institution_name']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>تاريخ الطلب</th>
                                        <td><?= date('Y-m-d H:i:s', strtotime($request['created_at'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


