# The Baker Best — Artisan Bakery Website

Multipage marketing website with online ordering inquiries, built on the LAMP stack per the technical specification.

## Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript (Swiper.js, GLightbox)
- **Backend:** PHP 8+ with PDO
- **Database:** MySQL 8+
- **Email:** PHPMailer (SMTP)

## Project Structure

```
Siya/
├── public/          ← Apache document root
├── sql/             ← Database schema and seed data
├── composer.json    ← PHP dependencies
└── README.md
```

## Local Setup (Windows)

### 1. Install prerequisites

- [XAMPP](https://www.apachefriends.org/) or [Laragon](https://laragon.org/) (Apache + PHP + MySQL)
- [Composer](https://getcomposer.org/)

### 2. Clone / copy project

Place the project at your preferred path, e.g. `C:\My Folder\Siya`.

### 3. Install PHP dependencies

```bash
cd "C:\My Folder\Siya"
composer install
```

### 4. Configure Apache

Point your virtual host **DocumentRoot** to the `public/` folder:

```
DocumentRoot "C:/My Folder/Siya/public"
<Directory "C:/My Folder/Siya/public">
    AllowOverride All
    Require all granted
</Directory>
```

Restart Apache after saving.

### 5. Create the database

Open phpMyAdmin or MySQL CLI and run:

```bash
mysql -u root -p < sql/schema.sql
mysql -u root -p < sql/seed.sql
```

### 6. Configure application

Copy the example config and edit credentials:

```bash
copy public\includes\config.example.php public\includes\config.php
```

Update `public/includes/config.php`:

| Setting | Description |
|---|---|
| `db.host`, `db.name`, `db.user`, `db.pass` | MySQL connection |
| `smtp.*` | SMTP credentials ([Mailtrap](https://mailtrap.io/) recommended for local testing) |
| `admin_email` | Where order/contact notifications are sent |
| `site_url` | Base URL, e.g. `http://localhost` or `http://bakerbest.test` |
| `debug` | Set `false` in production |

### 7. Visit the site

Open `http://localhost` (or your vhost URL) in a browser.

## Pages

| Page | URL |
|---|---|
| Home | `/index.php` |
| About Us | `/about.php` |
| Menu | `/menu.php` |
| Order Online | `/order.php` |
| Gallery | `/gallery.php` |
| Contact Us | `/contact.php` |

Clean URLs (e.g. `/menu`) work when Apache `mod_rewrite` is enabled.

## Features

- Responsive mobile-first design with warm artisan palette
- Hero image slider (Swiper.js)
- DB-driven menu with category filter and search
- Gallery with lightbox (GLightbox)
- Order and contact forms with client + server validation
- CSRF protection and rate limiting on form submissions
- MySQL storage for orders and contact messages
- Email notifications via PHPMailer/SMTP
- SEO: per-page meta, JSON-LD structured data, sitemap, robots.txt
- Accessibility: semantic HTML, ARIA labels, keyboard navigation, skip link

## Integrations

### Google Maps

The Contact page includes an embedded Google Maps iframe. Replace the address in `contact.php` with your real business location.

### Instagram Feed

The Home page has a placeholder for an Instagram widget. Replace the placeholder `div` in `index.php` with your [SnapWidget](https://snapwidget.com/) or [Elfsight](https://elfsight.com/instagram-feed-instashow/) embed code.

### Social Links

Update Instagram and Facebook URLs in `header.php` and `footer.php`.

## Production Deployment

1. Upload files to Linux hosting (document root = `public/`)
2. Run `composer install --no-dev` on the server
3. Import `sql/schema.sql` and `sql/seed.sql` (or production data)
4. Copy `config.example.php` → `config.php` with production credentials
5. Set `debug` => `false`
6. Enable HTTPS in `.htaccess` (uncomment the rewrite rules)
7. Update `site_url` in config and URLs in `sitemap.xml` / `robots.txt`
8. Point DNS A record to server IP and install SSL (Let's Encrypt)
9. Register site in Google Search Console and add GA4 tracking

## QA Checklist

- [ ] All 6 pages render correctly at 375px, 768px, 1024px, 1440px
- [ ] Mobile hamburger nav opens/closes and is keyboard accessible
- [ ] Menu filter and search work
- [ ] Gallery lightbox opens and navigates
- [ ] Order form validates, saves to DB, redirects to success page
- [ ] Contact form validates, saves to DB, redirects to success page
- [ ] Admin notification email received (with SMTP configured)
- [ ] CSRF rejection on tampered token
- [ ] Rate limit triggers after 5 submissions per hour
- [ ] Lighthouse scores acceptable for performance, accessibility, SEO

## License

Proprietary — The Baker Best / SweetByte Designs.
