<?php
/**
 * القائمة الجانبية المشتركة للوحة التحكم
 */
$currentPage = basename($_SERVER['PHP_SELF']);
$me = current_user();
?>
<style>
    .admin-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: 280px;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        padding: 20px 15px;
        box-shadow: -5px 0 30px rgba(0,0,0,0.3);
        z-index: 1000;
        overflow-y: auto;
    }
    
    .admin-sidebar::-webkit-scrollbar { width: 5px; }
    .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 5px; }
    
    .sidebar-header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(255,255,255,0.1);
        margin-bottom: 20px;
    }
    
    .sidebar-logo {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.5rem;
        color: white;
    }
    
    .sidebar-title { color: white; font-size: 1.2rem; font-weight: 700; margin: 0; }
    .sidebar-subtitle { color: rgba(255,255,255,0.6); font-size: 0.8rem; }
    
    .sidebar-user {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .sidebar-user-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 1.3rem;
        color: white;
    }
    
    .sidebar-user-name { color: white; font-weight: 600; margin: 0; font-size: 0.95rem; }
    .sidebar-user-role { color: rgba(255,255,255,0.6); font-size: 0.8rem; }
    
    .sidebar-menu { list-style: none; padding: 0; margin: 0; }
    .sidebar-menu-section { color: rgba(255,255,255,0.4); padding: 15px 15px 5px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; }
    
    .sidebar-menu-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        margin-bottom: 3px;
    }
    
    .sidebar-menu-link:hover,
    .sidebar-menu-link.active {
        background: rgba(255,255,255,0.1);
        color: white;
        transform: translateX(-3px);
    }
    
    .sidebar-menu-link.active { background: linear-gradient(135deg, #667eea, #764ba2); }
    .sidebar-menu-link i { width: 25px; font-size: 1rem; }
    .sidebar-menu-link.logout { color: #ef4444; }
    .sidebar-menu-link.logout:hover { background: rgba(239, 68, 68, 0.2); }
    
    .admin-main-content { margin-right: 280px; padding: 20px; min-height: 100vh; }
    
    @media (max-width: 1024px) {
        .admin-sidebar { transform: translateX(100%); transition: transform 0.3s ease; }
        .admin-sidebar.active { transform: translateX(0); }
        .admin-main-content { margin-right: 0; }
        .sidebar-toggle { display: block !important; }
    }
</style>

<div class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 class="sidebar-title">ExpEdu</h1>
        <p class="sidebar-subtitle">لوحة التحكم</p>
    </div>
    
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i class="fas fa-user-shield"></i>
        </div>
        <h3 class="sidebar-user-name"><?= htmlspecialchars($me['name']) ?></h3>
        <p class="sidebar-user-role"><?= $me['role'] === 'admin' ? 'مدير النظام' : 'مشرف' ?></p>
    </div>
    
    <ul class="sidebar-menu">
        <!-- لوحة التحكم -->
        <li><a href="dashboard.php" class="sidebar-menu-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i><span>لوحة التحكم</span>
        </a></li>
        
        <!-- إدارة المستخدمين -->
        <li class="sidebar-menu-section">إدارة المستخدمين</li>
        <li><a href="users.php" class="sidebar-menu-link <?= $currentPage === 'users.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i><span>المستخدمين</span>
        </a></li>
        <li><a href="register.php" class="sidebar-menu-link <?= $currentPage === 'register.php' ? 'active' : '' ?>">
            <i class="fas fa-user-plus"></i><span>إضافة مستخدم</span>
        </a></li>
        <li><a href="join_requests.php" class="sidebar-menu-link <?= $currentPage === 'join_requests.php' ? 'active' : '' ?>">
            <i class="fas fa-user-clock"></i><span>طلبات الانضمام</span>
        </a></li>
        
        <!-- إدارة المحتوى -->
        <li class="sidebar-menu-section">إدارة المحتوى</li>
        <li><a href="courses.php" class="sidebar-menu-link <?= $currentPage === 'courses.php' ? 'active' : '' ?>">
            <i class="fas fa-book"></i><span>الكورسات</span>
        </a></li>
        <li><a href="upload.php" class="sidebar-menu-link <?= $currentPage === 'upload.php' ? 'active' : '' ?>">
            <i class="fas fa-upload"></i><span>رفع كورس جديد</span>
        </a></li>
        <li><a href="ads.php" class="sidebar-menu-link <?= $currentPage === 'ads.php' ? 'active' : '' ?>">
            <i class="fas fa-bullhorn"></i><span>الإعلانات</span>
        </a></li>
        
        <!-- إدارة الموقع -->
        <li class="sidebar-menu-section">إدارة الموقع</li>
        <li><a href="manage_homepage.php" class="sidebar-menu-link <?= $currentPage === 'manage_homepage.php' ? 'active' : '' ?>">
            <i class="fas fa-home"></i><span>الصفحة الرئيسية</span>
        </a></li>
        <li><a href="manage_about.php" class="sidebar-menu-link <?= $currentPage === 'manage_about.php' ? 'active' : '' ?>">
            <i class="fas fa-info-circle"></i><span>من نحن</span>
        </a></li>
        <li><a href="manage_team.php" class="sidebar-menu-link <?= $currentPage === 'manage_team.php' ? 'active' : '' ?>">
            <i class="fas fa-user-tie"></i><span>الفريق</span>
        </a></li>
        <li><a href="manage_services.php" class="sidebar-menu-link <?= $currentPage === 'manage_services.php' ? 'active' : '' ?>">
            <i class="fas fa-concierge-bell"></i><span>الخدمات</span>
        </a></li>
        <li><a href="manage_fields.php" class="sidebar-menu-link <?= $currentPage === 'manage_fields.php' ? 'active' : '' ?>">
            <i class="fas fa-layer-group"></i><span>المجالات</span>
        </a></li>
        <li><a href="manage_statistics.php" class="sidebar-menu-link <?= $currentPage === 'manage_statistics.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i><span>الإحصائيات</span>
        </a></li>
        <li><a href="manage_testimonials.php" class="sidebar-menu-link <?= $currentPage === 'manage_testimonials.php' ? 'active' : '' ?>">
            <i class="fas fa-quote-right"></i><span>آراء العملاء</span>
        </a></li>
        <li><a href="manage_faq.php" class="sidebar-menu-link <?= $currentPage === 'manage_faq.php' ? 'active' : '' ?>">
            <i class="fas fa-question-circle"></i><span>الأسئلة الشائعة</span>
        </a></li>
        
        <!-- روابط سريعة -->
        <li class="sidebar-menu-section">روابط سريعة</li>
        <li><a href="../public/index.php" class="sidebar-menu-link" target="_blank">
            <i class="fas fa-external-link-alt"></i><span>عرض الموقع</span>
        </a></li>
        <li><a href="../public/logout.php" class="sidebar-menu-link logout">
            <i class="fas fa-sign-out-alt"></i><span>تسجيل الخروج</span>
        </a></li>
    </ul>
</div>

<button class="btn btn-primary sidebar-toggle" id="sidebarToggle" style="position: fixed; top: 10px; right: 10px; z-index: 1001; display: none;">
    <i class="fas fa-bars"></i>
</button>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    document.getElementById('adminSidebar').classList.toggle('active');
});
</script>


