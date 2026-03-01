-- ملف تحديث قاعدة البيانات
-- استخدم هذا الملف إذا كانت قاعدة البيانات موجودة بالفعل

USE expedun1_talent;

-- إضافة حقل رقم الهاتف إلى جدول المستخدمين (إذا لم يكن موجوداً)
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER password_hash;

-- يمكنك تشغيل هذا الاستعلام لإضافة أول مستخدم أدمن إذا لم يكن موجوداً:
-- INSERT INTO users (name, email, password_hash, role, created_at) 
-- VALUES ('المدير العام', 'admin@expedu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());
-- كلمة المرور الافتراضية: password

