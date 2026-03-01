-- جداول قاعدة البيانات الكاملة لبوابة خبرة
-- يجب تشغيل init_db.sql أولاً

USE expedun1_talent;

-- ===================================
-- 1. جدول المجالات (Fields)
-- ===================================
CREATE TABLE IF NOT EXISTS fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(200) NOT NULL,
    name_en VARCHAR(200),
    icon VARCHAR(100),
    description TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 2. جدول التخصصات (Specializations)
-- ===================================
CREATE TABLE IF NOT EXISTS specializations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field_id INT NOT NULL,
    name_ar VARCHAR(200) NOT NULL,
    name_en VARCHAR(200),
    description TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE CASCADE,
    INDEX idx_field (field_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 3. جدول الخدمات (Services)
-- ===================================
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    specialization_id INT NOT NULL,
    name_ar VARCHAR(200) NOT NULL,
    name_en VARCHAR(200),
    description TEXT,
    icon VARCHAR(100),
    price DECIMAL(10,2) DEFAULT 0.00,
    target_audience ENUM('student', 'jobseeker', 'freelancer', 'institution', 'all') DEFAULT 'all',
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE CASCADE,
    INDEX idx_specialization (specialization_id),
    INDEX idx_target (target_audience),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 4. جدول طلبات الخدمات (Service Requests)
-- ===================================
CREATE TABLE IF NOT EXISTS service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    user_type ENUM('student', 'graduate', 'employee', 'jobseeker', 'freelancer', 'institution'),
    field_id INT,
    specialization_id INT,
    message TEXT,
    status ENUM('pending', 'contacted', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES fields(id) ON DELETE SET NULL,
    FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created (created_at),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 5. جدول آراء العملاء (Testimonials)
-- ===================================
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    company VARCHAR(150),
    image VARCHAR(500),
    testimonial TEXT NOT NULL,
    rating INT DEFAULT 5,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_rating (rating),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 6. جدول أعضاء الفريق (Team Members)
-- ===================================
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    image VARCHAR(500),
    bio TEXT,
    linkedin VARCHAR(200),
    email VARCHAR(150),
    phone VARCHAR(20),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- 7. جدول الإحصائيات (Statistics)
-- ===================================
CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label_ar VARCHAR(100) NOT NULL,
    label_en VARCHAR(100),
    value VARCHAR(50) NOT NULL,
    icon VARCHAR(100),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- إدخال بيانات أولية
-- ===================================

-- المجالات الستة
INSERT INTO fields (name_ar, name_en, icon, display_order) VALUES
('هندسة الاتصالات وتقنية المعلومات', 'IT & Communications Engineering', 'fa-network-wired', 1),
('الهندسة الكيميائية وهندسة البتروكيماويات والعمليات', 'Chemical & Petrochemical Engineering', 'fa-flask', 2),
('الإنشاءات', 'Construction', 'fa-building', 3),
('اللغة الانجليزية', 'English Language', 'fa-language', 4),
('التصميم', 'Design', 'fa-palette', 5),
('إدارة الحسابات والتسويق', 'Accounting & Marketing', 'fa-chart-line', 6);

-- أعضاء الفريق
INSERT INTO team_members (name, position, display_order) VALUES
('م. سند العامري', 'المؤسس والرئيس التنفيذي', 1),
('م. هيثم العامري', 'المدير العام', 2),
('م. أزهار الحراصية', 'مدير جامعة مواهب خبرة', 3),
('أ. نعيمة الرواحية', 'مدير إدارة الهوية والتسويق', 4),
('م. صفية العمرية', 'مدير إدارة الدعم الفني والمالي', 5),
('م. نوف الصالحية', 'مدير خدمات مناسبات التخصصات', 6);

-- الإحصائيات
INSERT INTO statistics (label_ar, label_en, value, icon, display_order) VALUES
('خبير ومشرف', 'Experts & Supervisors', '+320', 'fa-user-tie', 1),
('مجال تخصصي', 'Specializations', '+13', 'fa-graduation-cap', 2),
('موسم تدريبي', 'Training Seasons', '+10', 'fa-calendar', 3),
('شراكات محلية', 'Local Partnerships', '+10', 'fa-handshake', 4),
('طالب مستفيد', 'Benefited Students', '+2200', 'fa-users', 5),
('مؤسسة وشركة متعاونة', 'Partner Organizations', '+10', 'fa-building', 6);

-- آراء تجريبية (يمكن تحديثها لاحقاً)
INSERT INTO testimonials (name, position, testimonial, rating, display_order) VALUES
('أحمد المعمري', 'طالب جامعي', 'بوابة خبرة غيرت مسار حياتي . الدعم والتوجيه الذي تلقيته كان استثنائياً، والخبراء يتمتعون بمهارات عالية وخبرة واسعة.', 5, 1),
('فاطمة الشكيلية', 'خريجة حديثة', 'ساعدتني بوابة خبرة في الحصول على وظيفة أحلامي. الإرشاد المهني والدورات التدريبية كانت في غاية الأهمية لتطوير مهاراتي.', 5, 2),
('محمد الحارثي', 'مدير موارد بشرية', 'تعاونا مع بوابة خبرة لتدريب فريقنا وكانت النتائج مذهلة. برامج تدريبية احترافية ومدربون أكفاء.', 5, 3);

COMMIT;

