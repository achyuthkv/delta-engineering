# Content Management

Projects and gallery photos are managed from `/admin` instead of by editing
PHP files. This covers:

- **Projects** (`projects.php`) — every bullet point in the accordion, grouped by category.
- **Gallery photos** — the Canada gallery and the Oman / India sections of the international gallery.

Everything else on the site (page copy, service pages, location pages, nav,
footer) is still plain PHP/HTML, same as before.

## One-time setup, once you have cPanel database access

1. **Create a MySQL database** via cPanel's "MySQL Database Wizard." Create a
   database user too, and add that user to the database with **All
   Privileges**. Note the three values cPanel gives you: database name,
   database username, database password (cPanel usually prefixes both the
   database and username with your cPanel account name, e.g.
   `cpaneluser_delta`).

2. **Import the schema, then the seed data**, in that order, via
   phpMyAdmin (Databases → your new database → Import) or the command line.
   The seed data is the site's *real, current* content — the same 80
   project entries and 54 gallery photos already live on the site — so the
   site keeps working exactly as it does today the moment this is imported.

   Command line (note the `--default-character-set=utf8mb4` — without it,
   the em/en-dashes and special characters in some entries get corrupted
   on import):
   ```
   mysql --default-character-set=utf8mb4 -u <cpanel_db_user> -p <cpanel_db_name> < admin/sql/schema.sql
   mysql --default-character-set=utf8mb4 -u <cpanel_db_user> -p <cpanel_db_name> < admin/sql/seed.sql
   ```
   In phpMyAdmin: on the Import tab, make sure "Character set of the file"
   is set to `utf8mb4` before importing each file.

3. **Copy the credentials file** and fill in the real values:
   ```
   cp admin/db-credentials.example.php admin/db-credentials.php
   ```
   Edit `admin/db-credentials.php` with the database name/user/password from
   step 1. This file is gitignored — it never gets committed, so don't lose
   your only copy.

4. **Visit `https://www.delta-engineering.ca/admin/setup.php`** and create
   your admin username and password. This page works exactly once — after
   the first account exists, it refuses to run again (so no one else can use
   it to create a second account later). Then log in at `admin/login.php`.

## Day to day use

- **Add/edit/delete a project entry**: Admin → Projects. Each entry belongs
  to a category (the accordion heading) — pick an existing one from the
  suggestions or type a new one to start a new accordion section.
- **Add/edit/delete a gallery photo**: Admin → Gallery. Choose which office
  (Canada / Oman / India) it belongs to — that's what determines which page
  and section it shows up on. Uploads are validated as real images (not
  just checked by file extension) and capped at 10MB.
- **Hide something without deleting it**: uncheck "Published" on either
  form. Hidden items stay in the database but won't render on the live site.
- **Reordering**: the "Sort order" field controls display order (lower
  numbers first) within a category or office.

## If you lose the admin password

There's no self-service password reset (it's a single-admin internal tool,
not a public account system). To reset it, open phpMyAdmin, run:

```sql
UPDATE admin_users SET password_hash = '<new hash>' WHERE username = 'yourusername';
```

Generate `<new hash>` by running this once, anywhere PHP is available, and
pasting the output:

```php
<?php echo password_hash('your-new-password', PASSWORD_DEFAULT);
```

## Notes

- `/admin/` is blocked from search engine indexing via `robots.txt`, but
  that's not a security measure — the login system is. Don't treat
  robots.txt as access control.
- Uploaded photos land in `assets/images/gallery_uploads/` and are not
  tracked in git (same reasoning as any user-uploaded content) — that's
  expected, not a bug.
- If `admin/db-credentials.php` is missing or the database is unreachable,
  `projects.php` and the two gallery pages fail gracefully with a "temporarily
  unavailable" message instead of crashing the whole page.
