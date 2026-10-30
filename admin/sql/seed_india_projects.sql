-- Real India project data supplied by the client (Oct 2026), replacing the
-- six placeholder rows that were seeded before any confirmed India projects
-- existed. Run after migrate_add_categories.sql.
--
-- Placeholders are unpublished rather than deleted, in case any of them
-- need to be referenced again later.
UPDATE projects SET is_published = 0 WHERE office = 'india' AND description LIKE 'Placeholder project example%';

INSERT INTO project_categories (office, name, sort_order) VALUES
	('india', 'Residential Projects', 1),
	('india', 'Commercial Projects', 2),
	('india', 'Plotted Development', 3),
	('india', 'Structural Analysis', 4)
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'Skanda Acropolis: 1,57,000 Sft, 2B+G+Stilt+14+Terrace. Scope: FMEP', 'india', 'bim_mep', 0, 1
FROM project_categories WHERE office = 'india' AND name = 'Residential Projects';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'RRN Lux: 40,500 Sft built-up area. Scope: Structural, FMEP, PMC', 'india', 'structural', 1, 1
FROM project_categories WHERE office = 'india' AND name = 'Residential Projects';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'ALSS Commercial: B+G+3+Terrace, 28,190 Sft. Scope: FMEP, PMC', 'india', 'bim_mep', 0, 1
FROM project_categories WHERE office = 'india' AND name = 'Commercial Projects';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'Brigade Software Tech Park. Scope: HVAC', 'india', 'bim_mep', 1, 1
FROM project_categories WHERE office = 'india' AND name = 'Commercial Projects';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'Nuvana Plotted Development: 11.32 Acres. Scope: FMEP', 'india', 'bim_mep', 0, 1
FROM project_categories WHERE office = 'india' AND name = 'Plotted Development';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'PAL Plotted Development: 12 Acres. Scope: FMEP', 'india', 'bim_mep', 1, 1
FROM project_categories WHERE office = 'india' AND name = 'Plotted Development';

INSERT INTO projects (category_id, category, description, office, discipline, sort_order, is_published)
SELECT id, '', 'Residential project for Structural Study: B+G+3+Terrace', 'india', 'structural', 0, 1
FROM project_categories WHERE office = 'india' AND name = 'Structural Analysis';
