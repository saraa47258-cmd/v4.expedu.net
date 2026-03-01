-- جدول طلبات الانضمام لبوابة خبرة
USE expedun1_talent;

CREATE TABLE IF NOT EXISTS join_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_type ENUM('expert', 'supervisor', 'institution', 'learner') NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    
    -- للخبراء
    specialization VARCHAR(200) NULL,
    experience_description TEXT NULL,
    cv_file_path VARCHAR(500) NULL,
    
    -- للمشرفين
    supervision_field VARCHAR(200) NULL,
    previous_supervision_experience TEXT NULL,
    
    -- للمؤسسات
    institution_name VARCHAR(200) NULL,
    responsible_person VARCHAR(150) NULL,
    institution_type ENUM('company', 'government', 'nonprofit') NULL,
    services_required TEXT NULL,
    
    -- للمتعلمين
    learner_category ENUM('student', 'graduate', 'employee', 'job_seeker') NULL,
    learning_field VARCHAR(200) NULL,
    skills_required TEXT NULL,
    specific_service VARCHAR(500) NULL,
    
    status ENUM('pending', 'contacted', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_request_type (request_type),
    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

