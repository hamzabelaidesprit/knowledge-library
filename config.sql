CREATE TABLE specialities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    is_admin BOOLEAN DEFAULT 0,
    speciality_id INT,
    FOREIGN KEY (speciality_id) REFERENCES specialities(id)
);

CREATE TABLE pdf_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    filename VARCHAR(255),
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    assigned_speciality_id INT,
    FOREIGN KEY (assigned_speciality_id) REFERENCES specialities(id)
);

CREATE TABLE employee_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    file_id INT,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (file_id) REFERENCES pdf_files(id)
);
