-- Delta Engineering Services -- content management schema
-- Run this once against a fresh database, then run seed.sql to load the
-- current site content so nothing goes blank when the CMS goes live.

CREATE TABLE IF NOT EXISTS admin_users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One row per line item in the Projects accordion (projects.php).
-- "category" groups items under an accordion heading, e.g. "Infrastructure
-- Projects" -- there's no separate categories table since the same free-form
-- grouping the site already uses is all that's needed; the admin UI offers
-- existing category names as suggestions to avoid accidental duplicates.
CREATE TABLE IF NOT EXISTS projects (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	category VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	sort_order INT NOT NULL DEFAULT 0,
	is_published TINYINT(1) NOT NULL DEFAULT 1,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	KEY idx_category_sort (category, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One row per photo across all three gallery pages (Canada / Oman / India).
CREATE TABLE IF NOT EXISTS gallery_photos (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	office ENUM('canada','oman','india') NOT NULL,
	image_path VARCHAR(500) NOT NULL,
	alt_text VARCHAR(255) NOT NULL,
	title VARCHAR(255) NOT NULL,
	location VARCHAR(255) NOT NULL,
	sort_order INT NOT NULL DEFAULT 0,
	is_published TINYINT(1) NOT NULL DEFAULT 1,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	KEY idx_office_sort (office, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
