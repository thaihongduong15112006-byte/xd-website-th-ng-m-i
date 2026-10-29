CREATE DATABASE IF NOT EXISTS website_ban_hang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE website_ban_hang;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 fullname VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','user') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL
);
CREATE TABLE IF NOT EXISTS products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(200) NOT NULL,
 description TEXT,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 image VARCHAR(255) DEFAULT '',
 category_id INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO categories(name) SELECT 'Điện thoại' WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name='Điện thoại');
INSERT INTO categories(name) SELECT 'Laptop' WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name='Laptop');
INSERT INTO categories(name) SELECT 'Phụ kiện' WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name='Phụ kiện');
INSERT INTO categories(name) SELECT 'Đồng hồ thông minh' WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name='Đồng hồ thông minh');

INSERT INTO products(name,description,price,image,category_id)
SELECT 'Điện thoại thông minh X1','Điện thoại mẫu có màn hình đẹp, phù hợp học tập và giải trí.',3990000,'assets/images/phone.svg',id FROM categories WHERE name='Điện thoại' AND NOT EXISTS (SELECT 1 FROM products WHERE name='Điện thoại thông minh X1');
INSERT INTO products(name,description,price,image,category_id)
SELECT 'Laptop Student 14','Laptop minh họa dành cho sinh viên học tập, làm bài và giải trí.',12990000,'assets/images/laptop.svg',id FROM categories WHERE name='Laptop' AND NOT EXISTS (SELECT 1 FROM products WHERE name='Laptop Student 14');
INSERT INTO products(name,description,price,image,category_id)
SELECT 'Tai nghe không dây H2','Tai nghe không dây minh họa, thiết kế gọn nhẹ.',350000,'assets/images/headphones.svg',id FROM categories WHERE name='Phụ kiện' AND NOT EXISTS (SELECT 1 FROM products WHERE name='Tai nghe không dây H2');
INSERT INTO products(name,description,price,image,category_id)
SELECT 'Đồng hồ thông minh S1','Đồng hồ thông minh minh họa với màn hình hiển thị giờ.',890000,'assets/images/watch.svg',id FROM categories WHERE name='Đồng hồ thông minh' AND NOT EXISTS (SELECT 1 FROM products WHERE name='Đồng hồ thông minh S1');
