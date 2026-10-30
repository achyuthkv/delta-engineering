-- Adds a real categories table so categories can be managed (created,
-- renamed, reordered, deleted, given an icon) from the admin panel instead
-- of being an implicit free-form string on each project row.
--
-- projects.category (the old text column) is left in place rather than
-- dropped -- it's no longer read by the site once this migration's PHP
-- ships, but keeping it avoids a destructive change to production data.
-- category_id is the new source of truth.

CREATE TABLE IF NOT EXISTS project_categories (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	office ENUM('canada','india') NOT NULL DEFAULT 'canada',
	name VARCHAR(255) NOT NULL,
	icon_path VARCHAR(500) NULL,
	sort_order INT NOT NULL DEFAULT 0,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	UNIQUE KEY uniq_office_name (office, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE projects ADD COLUMN category_id INT UNSIGNED NULL AFTER category;
ALTER TABLE projects ADD KEY idx_category_id (category_id);
ALTER TABLE projects ADD CONSTRAINT fk_projects_category_id
	FOREIGN KEY (category_id) REFERENCES project_categories(id) ON DELETE SET NULL;

-- Backfill: one category row per distinct (office, category) pair already
-- in use, numbered in the same alphabetical order the site already shows
-- them in -- so nothing visibly reorders the moment this ships. Admin can
-- reorder from here on.
SET @rank := 0;
INSERT INTO project_categories (office, name, sort_order)
SELECT office, category, (@rank := @rank + 1)
FROM (SELECT DISTINCT office, category FROM projects ORDER BY office, category) AS d;

UPDATE projects p
JOIN project_categories c ON c.office = p.office AND c.name = p.category
SET p.category_id = c.id;
