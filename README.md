# Usman Farooq - Dynamic Portfolio Website

Web Engineering - Complex Engineering Problem (Mehran UET, Jamshoro)
Built with **HTML5, CSS3, Bootstrap 5, JavaScript, PHP and MySQL**.

## 1. What the website does

| Page | File | Content source |
|---|---|---|
| Home / About | `index.php` | Static bio + latest 3 projects and education from the database |
| Skills & Education | `skills.php` | Skills, education, experience and certificates from the database |
| Projects | `projects.php` | All projects from the database, with live search and technology filter |
| Contact | `contact.php` | Contact form (JS + PHP validation) that saves messages in the database |
| Admin panel | `admin/` | Password-protected CRUD for projects, message inbox, change password |

Adding a project in the admin panel updates the public pages immediately, without editing any HTML.

## 2. How everything works together

```
Browser  --->  Apache (XAMPP)  --->  PHP pages  --->  MySQL (phpMyAdmin)
                                       |
                includes/db.php  (connection)   includes/functions.php (helpers, CSRF, validation)
                includes/header.php + footer.php (same navbar on every page)
```

1. The browser requests `http://localhost/portfolio/projects.php`.
2. Apache hands the `.php` file to PHP.
3. PHP opens the database through `includes/db.php`, runs a prepared query and loops over the rows.
4. PHP prints HTML (every value passed through `e()` / `htmlspecialchars`) and sends it back.
5. Bootstrap CSS, `style.css` and `script.js` make it responsive and interactive in the browser.

PHP files must be opened through `http://localhost/...` (Apache). Double-clicking a `.php` file or using VS Code Live Server will not run PHP.

## 3. Folder structure

```
portfolio/
  index.php  skills.php  projects.php  contact.php
  includes/   db.php  functions.php  header.php  footer.php  admin_header.php  admin_footer.php
  admin/      login.php  dashboard.php  add_project.php  edit_project.php  delete_project.php
              messages.php  change_password.php  logout.php  _project_form.php
  assets/     css/style.css   js/script.js   img/usman.jpg   certificates/   vendor/bootstrap/
              Usman-Farooq-CV.pdf
  database/   portfolio_db.sql   (schema + sample data)
  README.md
```

Bootstrap is stored locally in `assets/vendor/bootstrap`, so the site works without internet.

## 4. Setup on XAMPP (Windows)

1. Install XAMPP and open the **XAMPP Control Panel**. Start **Apache** and **MySQL**.
2. Copy the whole `portfolio` folder to `C:\xampp\htdocs\portfolio`.
3. Open **http://localhost/portfolio/**. On the first visit the website creates the database
   `portfolio_db`, all tables and the sample records automatically (from `database/portfolio_db.sql`).
   You can also import that file manually in phpMyAdmin (*Import* tab) if you prefer.
4. If your MySQL has a password, edit it in `includes/db.php` (XAMPP default: user `root`, empty password).
5. Admin panel: **http://localhost/portfolio/admin/login.php**

To update the project later, simply replace the `portfolio` folder in `htdocs`. The existing database
(and the data you added) is kept. To reset the sample data, delete the database `portfolio_db` in
phpMyAdmin and refresh the site; it is created again.

### Admin login (for the demo)

| Username | Password |
|---|---|
| `admin` | `Admin@123` |

Change it after the first login: *Admin panel > Change password*. (Passwords are stored as bcrypt hashes, never as plain text.)

## 5. Using the admin panel

- **Dashboard**: list of all projects with *Edit* and *Delete* buttons.
- **Add project**: title, description, technologies (comma separated), optional GitHub/live link.
- **Edit project**: change any field and save. **Delete** asks for confirmation.
- **Messages**: every message sent from the contact form, with a delete button.

## 6. Requirement checklist

| # | Requirement | Where it is implemented |
|---|---|---|
| 1 | 3-4 pages, semantic HTML, consistent navigation | 4 pages sharing `header.php` / `footer.php`; `header`, `nav`, `main`, `section`, `article`, `footer` elements |
| 2 | Responsive UI with Bootstrap | Bootstrap navbar, grid, cards, forms; tested down to phone width |
| 3 | Dynamic content from a relational DB | tables `projects`, `skills`, `education`, `experience`, `certificates` |
| 4 | Password-protected CRUD admin | `admin/` (sessions, bcrypt, CSRF tokens, server-side validation) |
| 5 | Contact form + JS and PHP validation, saved to DB | `contact.php`, `assets/js/script.js`, table `messages` |
| 6 | At least two JavaScript features | theme toggle, project search + filter, form validation, character counter, collapsible navbar |
| 7 | PHP logic, prepared statements, escaping | every query uses `prepare()` + `bind_param()`; every output uses `e()` |
| 8 | Testing | Section 8 below |
| 9 | Deployment on XAMPP + instructions | Section 4 |
| 10 | Individuality | own biography, projects, skills, certificates and design |

## 7. Security measures

- Prepared statements for every query that uses user input (prevents SQL injection).
- Output escaping with `htmlspecialchars` (prevents XSS).
- CSRF token on every form (contact, login, add, edit, delete, logout, change password).
- Passwords hashed with `password_hash()` and checked with `password_verify()`.
- `session_regenerate_id()` after login; admin pages call `require_admin()` first.
- Login lock for 60 seconds after 5 failed attempts; one generic error message for wrong user or password.
- Delete actions accept POST requests only.
- Honeypot field on the contact form to stop simple bots.
- `.htaccess` blocks direct web access to `includes/` and `database/`.

## 8. Test plan (take a screenshot of each result for the report)

| # | Test | Steps | Expected result |
|---|---|---|---|
| T1 | Navigation | Click every navbar link | All 4 pages open, active link highlighted |
| T2 | Responsive layout | Resize browser / open DevTools mobile view | Navbar collapses into a menu, cards stack in one column |
| T3 | Dynamic projects | Open Projects page | Cards match the `projects` table |
| T4 | Admin without login | Open `/admin/dashboard.php` directly | Redirected to the login page |
| T5 | Wrong login | Login with a wrong password | "Invalid username or password." |
| T6 | Correct login | Login as admin | Dashboard opens |
| T7 | Add project | Fill the form and save | Success message, new card appears on Projects and Home |
| T8 | Invalid project | Short title, bad URL | Validation errors shown, nothing saved |
| T9 | Edit project | Change a title and save | Public page shows the new title |
| T10 | Delete project | Click Delete and confirm | Project disappears from admin and public pages |
| T11 | Contact validation (JS) | Submit an empty form / bad email | Red messages appear, form is not sent |
| T12 | Contact validation (PHP) | Disable JavaScript and submit bad data | Server-side errors shown |
| T13 | Contact success | Submit valid data | Success message; row visible in phpMyAdmin `messages` and in Admin > Messages |
| T14 | Search and filter | Type in the search box, click a technology button | Cards are filtered, counter updates |
| T15 | Theme toggle | Click Light / Dark | Theme changes and is remembered after reload |
| T16 | SQL injection attempt | Login with `admin' OR '1'='1` | Login fails |
| T17 | XSS attempt | Add a project titled `<script>alert(1)</script>` | Text is displayed as text, no alert |

All of these were run against PHP 8.3 + MariaDB 10.11 during development (T4-T10, T12, T13, T16, T17 through HTTP requests).

## 9. Troubleshooting

- **Apache will not start (port 80 busy):** change `Listen 80` to `Listen 8080` in XAMPP *Config > httpd.conf*, then use `http://localhost:8080/portfolio/`.
- **"Database connection failed":** MySQL is not started in XAMPP, or the MySQL password in `includes/db.php` is different.
- **PHP code appears as text:** the page was not opened through `http://localhost/...`.
- **Old design after an update:** press `Ctrl + Shift + R`.
- **Reset the sample data:** delete the database `portfolio_db` in phpMyAdmin and refresh the site, or import `database/portfolio_db.sql` again.
