-- جدول الاشتراكات في الكورسات
-- يربط بين المستخدمين والكورسات

CREATE TABLE IF NOT EXISTS `enrollments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL COMMENT 'معرف المستخدم',
    `course_id` INT UNSIGNED NOT NULL COMMENT 'معرف الكورس',
    `status` ENUM('active', 'expired', 'cancelled', 'pending') DEFAULT 'pending' COMMENT 'حالة الاشتراك',
    `payment_status` ENUM('paid', 'pending', 'refunded') DEFAULT 'pending' COMMENT 'حالة الدفع',
    `payment_amount` DECIMAL(10, 2) DEFAULT 0.00 COMMENT 'مبلغ الدفع',
    `payment_method` VARCHAR(50) NULL COMMENT 'طريقة الدفع',
    `transaction_id` VARCHAR(255) NULL COMMENT 'معرف العملية المالية',
    `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الاشتراك',
    `expires_at` TIMESTAMP NULL COMMENT 'تاريخ انتهاء الاشتراك',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- مفاتيح أجنبية
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE,
    
    -- منع التكرار
    UNIQUE KEY `unique_enrollment` (`user_id`, `course_id`),
    
    -- فهارس للبحث السريع
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_course_id` (`course_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة تعليق على الجدول
ALTER TABLE `enrollments` COMMENT = 'جدول اشتراكات المستخدمين في الكورسات';


