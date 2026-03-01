-- تغيير كلمة مرور الأدمن
-- استخدم هذا الملف لتغيير كلمة مرور حساب الأدمن

USE expedun1_talent;

-- تغيير كلمة المرور للأدمن
-- كلمة المرور الجديدة: admin123
-- يمكنك استبدال الـ hash بكلمة مرور جديدة (انظر الملاحظات أدناه)

UPDATE users 
SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email = 'admin@expedu.com' AND role = 'admin';

-- عرض معلومات الأدمن بعد التحديث
SELECT id, name, email, role, created_at 
FROM users 
WHERE email = 'admin@expedu.com';

-- ============================================
-- لإنشاء hash جديد لكلمة مرور مخصصة:
-- ============================================
-- 1. استخدم السكريبت التالي في PHP:
--    php -r "echo password_hash('كلمة_المرور_الجديدة', PASSWORD_DEFAULT);"
--
-- 2. أو استخدم الملف generate_password_hash.php (سيتم إنشاؤه تلقائياً)
--
-- 3. انسخ الـ hash الناتج واستبدله في السطر 11 أعلاه
-- ============================================

