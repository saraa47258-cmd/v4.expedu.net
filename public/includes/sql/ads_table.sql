-- جدول الإعلانات والعروض

USE expedun1_talent;

-- إنشاء جدول الإعلانات
CREATE TABLE IF NOT EXISTS ads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  button_text VARCHAR(100) DEFAULT 'اعرف المزيد',
  button_link VARCHAR(500),
  image_url VARCHAR(500),
  display_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إدراج بيانات تجريبية
INSERT INTO ads (title, description, button_text, button_link, image_url, display_order, is_active) VALUES
('عرض خاص', 'احصل على خصم 30% على جميع الدورات', 'اعرف المزيد', 'courses.php', 'assets/images/ad1.jpg', 1, 1),
('دورة جديدة', 'انضم إلى دورة التطوير المهني', 'سجل الآن', 'courses.php', 'assets/images/ad2.jpg', 2, 1),
('استشارة مجانية', 'احجز استشارة مع خبرائنا', 'احجز الآن', '#contact', 'assets/images/ad3.jpg', 3, 1);


