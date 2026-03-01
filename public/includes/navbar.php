<?php
/**
 * Navbar مشترك — بوابة خبرة
 * يُستعمل في جميع الصفحات العامة
 * 
 * المتغيرات المتاحة قبل include:
 *   $currentPage  — اسم الصفحة الحالية (مثل 'index', 'about', 'courses')
 *   $currentUser  — بيانات المستخدم الحالي أو null
 */

// Safe defaults
if (!isset($currentPage)) {
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
}
if (!isset($currentUser)) {
    $currentUser = function_exists('current_user') ? current_user() : null;
}

// Navigation items
$navItems = [
    ['id' => 'index',    'label' => 'الرئيسية',        'icon' => 'fas fa-home',            'href' => 'index.php'],
    ['id' => 'about',    'label' => 'من نحن',           'icon' => 'fas fa-info-circle',     'href' => 'about.php'],
    ['id' => 'services', 'label' => 'خدماتنا',          'icon' => 'fas fa-concierge-bell',  'href' => 'services.php'],
    ['id' => 'team',     'label' => 'فريقنا',           'icon' => 'fas fa-users',           'href' => 'team.php'],
    ['id' => 'courses',  'label' => 'الكورسات',         'icon' => 'fas fa-graduation-cap',  'href' => 'courses.php'],
    ['id' => 'faq',      'label' => 'الأسئلة الشائعة',  'icon' => 'fas fa-question-circle', 'href' => 'faq.php'],
];
?>

<!-- Premium Navbar -->
<nav class="premium-navbar" id="premiumNavbar">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">
            <img src="assets/icons/logo.png" alt="بوابة خبرة" class="brand-logo-img">
            <span class="brand-name">بوابة خبرة</span>
        </a>

        <!-- Desktop Nav -->
        <div class="nav-center">
            <ul class="nav-menu">
                <?php foreach ($navItems as $item): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === $item['id'] || ($currentPage === 'course' && $item['id'] === 'courses')) ? 'active' : '' ?>" href="<?= $item['href'] ?>">
                        <i class="<?= $item['icon'] ?>"></i>
                        <span><?= $item['label'] ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Desktop Actions -->
        <div class="nav-actions">
            <?php if ($currentUser): ?>
                <div class="nav-user-dropdown" id="userDropdown">
                    <button class="nav-user-btn" onclick="toggleUserDropdown()">
                        <span class="user-avatar"><i class="fas fa-user"></i></span>
                        <span><?= htmlspecialchars($currentUser['name']) ?></span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="user-dropdown-menu">
                        <?php if (in_array($currentUser['role'] ?? '', ['admin', 'instructor'])): ?>
                            <a href="../admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
                        <?php else: ?>
                            <a href="user_dashboard.php"><i class="fas fa-user-circle"></i> حسابي</a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="nav-auth">
                    <a href="user_login.php" class="nav-login-btn"><i class="fas fa-sign-in-alt"></i> دخول</a>
                    <a href="join.php" class="nav-cta-btn"><i class="fas fa-rocket"></i> انضم إلينا</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mobile Toggle -->
        <button class="nav-toggle" id="navToggle" onclick="toggleMobileNav()" aria-label="القائمة">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Mobile Overlay -->
    <div class="nav-overlay" id="navOverlay" onclick="closeMobileNav()"></div>

    <!-- Mobile Drawer -->
    <div class="nav-drawer" id="navDrawer">
        <div class="drawer-header">
            <a href="index.php" class="drawer-brand">
                <img src="assets/icons/logo.png" alt="بوابة خبرة">
                <span>بوابة خبرة</span>
            </a>
            <button class="drawer-close" onclick="closeMobileNav()" aria-label="إغلاق"><i class="fas fa-times"></i></button>
        </div>

        <ul class="drawer-nav">
            <?php foreach ($navItems as $item): ?>
            <li>
                <a class="nav-link <?= ($currentPage === $item['id'] || ($currentPage === 'course' && $item['id'] === 'courses')) ? 'active' : '' ?>" href="<?= $item['href'] ?>">
                    <i class="<?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($currentUser): ?>
            <div class="drawer-user">
                <div class="drawer-user-info">
                    <div class="drawer-user-avatar"><i class="fas fa-user"></i></div>
                    <span class="drawer-user-name"><?= htmlspecialchars($currentUser['name']) ?></span>
                </div>
                <div class="drawer-user-links">
                    <?php if (in_array($currentUser['role'] ?? '', ['admin', 'instructor'])): ?>
                        <a href="../admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
                    <?php else: ?>
                        <a href="user_dashboard.php"><i class="fas fa-user-circle"></i> حسابي</a>
                    <?php endif; ?>
                    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
                </div>
            </div>
        <?php else: ?>
            <div class="drawer-actions">
                <a href="join.php" class="nav-cta-btn"><i class="fas fa-rocket"></i> انضم إلينا</a>
                <a href="user_login.php" class="nav-login-btn"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<script>
// Mobile nav toggle
function toggleMobileNav() {
    const toggle = document.getElementById('navToggle');
    const drawer = document.getElementById('navDrawer');
    const overlay = document.getElementById('navOverlay');
    const isOpen = drawer.classList.contains('open');
    
    if (isOpen) {
        closeMobileNav();
    } else {
        toggle.classList.add('active');
        drawer.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileNav() {
    const toggle = document.getElementById('navToggle');
    const drawer = document.getElementById('navDrawer');
    const overlay = document.getElementById('navOverlay');
    
    toggle.classList.remove('active');
    drawer.classList.remove('open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

// User dropdown toggle (desktop)
function toggleUserDropdown() {
    const dd = document.getElementById('userDropdown');
    dd.classList.toggle('open');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dd = document.getElementById('userDropdown');
    if (dd && !dd.contains(e.target)) {
        dd.classList.remove('open');
    }
});

// Scroll effect
window.addEventListener('scroll', function() {
    const nav = document.getElementById('premiumNavbar');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

// Close mobile nav on resize to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 992) {
        closeMobileNav();
    }
});

// Close mobile nav on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileNav();
        const dd = document.getElementById('userDropdown');
        if (dd) dd.classList.remove('open');
    }
});
</script>
