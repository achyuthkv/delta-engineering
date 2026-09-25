-- One-time migration: adds the ability to attach photos to a project entry
-- from the Projects admin, so they also show up on the gallery page for
-- that project's office. Only needed on a database that already has the
-- gallery_photos table (i.e. every live site as of September 2026) --
-- schema.sql already includes this column for anyone setting up fresh.
--
-- Run this once via phpMyAdmin (your database -> SQL tab) or:
--   mysql --default-character-set=utf8mb4 -u <cpanel_db_user> -p <cpanel_db_name> < admin/sql/migrate_add_project_photos.sql

ALTER TABLE gallery_photos
	ADD COLUMN project_id INT UNSIGNED NULL AFTER office,
	ADD KEY idx_project_id (project_id),
	ADD CONSTRAINT fk_gallery_photos_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL;
