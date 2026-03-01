-- ملف إنشاء أول مستخدم أدمن
-- استخدم هذا الملف لإنشاء حساب أدمن بسرعة

USE expedun1_talent;

-- إنشاء مستخدم أدمن
-- البريد: admin@expedu.com
-- كلمة المرور: password
INSERT INTO users (name, email, password_hash, phone, role, created_at) 
VALUES (
    'المدير العام', 
    'admin@expedu.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NULL,
    'admin', 
    NOW()
);

-- عرض المستخدم المُنشأ
SELECT id, name, email, role, created_at FROM users WHERE email = 'admin@expedu.com';


