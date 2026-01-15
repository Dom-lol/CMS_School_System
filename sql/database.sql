
-- ២. Table សម្រាប់គណនីប្រើប្រាស់ (ស៊ីគ្នាជាមួយ Login UI)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'staff', 'teacher', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ៣. Table សម្រាប់ព័ត៌មានគ្រូបង្រៀន
CREATE TABLE teachers (
    teacher_id VARCHAR(20) PRIMARY KEY, -- ឧទាហរណ៍: T001
    user_id INT,
    major VARCHAR(100), -- ឯកទេស
    phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ៤. Table សម្រាប់ព័ត៌មានសិស្ស (សម្រាប់ UI Student Registration)
CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY, -- ឧទាហរណ៍: S001
    user_id INT,
    gender ENUM('Male', 'Female'),
    dob DATE,
    address TEXT,
    phone VARCHAR(20),
    class_name VARCHAR(20), -- ឧទាហរណ៍: 12A
    academic_year VARCHAR(20), -- ឧទាហរណ៍: 2023-2024
    status ENUM('Learning', 'Suspended', 'Stopped') DEFAULT 'Learning',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ៥. Table សម្រាប់មុខវិជ្ជា
CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(100), -- ខ្មែរ, គណិត, រូបវិទ្យា...
    teacher_id VARCHAR(20),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
);

-- ៦. Table សម្រាប់បញ្ចូលពិន្ទុ (សម្រាប់ UI Teacher Score Input)
CREATE TABLE scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(20),
    subject_id INT,
    monthly_score DECIMAL(5,2),
    exam_score DECIMAL(5,2),
    total_score DECIMAL(5,2),
    grade CHAR(1), -- A, B, C, D, E, F
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- ៧. Table សម្រាប់កាលវិភាគ (សម្រាប់ UI Timetable)
CREATE TABLE timetable (
    id INT PRIMARY KEY AUTO_INCREMENT,
    day_of_week VARCHAR(20), -- Monday, Tuesday...
    time_slot VARCHAR(50), -- 08:00 AM - 09:00 AM
    class_name VARCHAR(20),
    subject_id INT,
    teacher_id VARCHAR(20),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id)
);

-- ៨. បញ្ចូលទិន្នន័យសាកល្បង (Password គឺ: 123456)
INSERT INTO users (username, password, full_name, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin'),
('staff01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sokha Staff', 'staff'),
('teacher01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Chantha', 'teacher'),
('student01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kosal Student', 'student');