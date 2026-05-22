<div align="center">
  <img src="public/images/logo.png" alt="BSP Zapin Logo" width="140" />

  <h1>BSP Zapin</h1>
  <p><strong>Corporate Website, CMS, Operational Monitoring, and Whistleblowing System</strong></p>
  <p>Built with Laravel 12 &middot; Tailwind CSS 4 &middot; Vite &middot; MySQL</p>

  <br />

  ![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
  ![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
  ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
  ![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
  ![Queue](https://img.shields.io/badge/Queue-Database_Driver-blue?style=flat-square)
</div>

---

## Table of Contents

- [Overview](#overview)
- [Main Features](#main-features)
- [System Architecture](#system-architecture)
- [User Roles](#user-roles)
- [Technology Stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Running the Application](#running-the-application)
- [Production Deployment](#production-deployment)
- [Queue and Cron Job](#queue-and-cron-job)
- [SEO and Google Search Console](#seo-and-google-search-console)
- [Module Documentation](#module-documentation)
- [Security Notes](#security-notes)
- [Directory Structure](#directory-structure)
- [Database Schema](#database-schema)
- [Maintenance Commands](#maintenance-commands)
- [Common Production Issues](#common-production-issues)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

BSP Zapin is a full-stack Laravel 12 web application built for PT Bumi Siak Pusako Zapin. The system acts as a public corporate website, internal content management system, operational monitoring platform, and Whistleblowing System.

The application supports bilingual public content using Indonesian and English locale prefixes. Public pages are accessible through `/id` and `/en`. Internal modules are separated by user role, including Admin, Writer, Reviewer, Operational, WBS Admin, WBS Officer, and Pelapor.

The system includes CMS management, news publication workflow, operational data charts, fullscreen TV display, WBS report lifecycle, email notifications, PDF export, Excel export, image upload optimization, queue-based background processing, and SEO support through sitemap and robots configuration.

---

## Main Features

### Public Website

The public website is available in Indonesian and English.

Main public routes include:

```txt
/id
/en
/id/media-publikasi
/en/media-publikasi
/id/wbs
/en/wbs
/id/gcg
/en/gcg
/id/operasional
/en/operasional
/id/hubungan-investor
/en/hubungan-investor
/id/tjsl
/en/tjsl
/sitemap.xml
/robots.txt
```

Public website features:

- Dynamic homepage slider.
- Company profile pages.
- Bilingual CMS pages.
- News and media publication.
- TJSL program publication.
- GCG document library.
- Investor relations document repository.
- Operational public summary.
- WBS public landing page.
- Legal pages such as privacy policy and terms.
- Auto-generated XML sitemap for Google indexing.
- SEO meta title, description, and image support.

---

### Admin Panel

Accessible through:

```txt
/admin
```

Reserved for users with the `admin` role.

Admin features:

- Dashboard summary.
- News management.
- Page management.
- Profile page management.
- Slider management.
- Partner management.
- Menu management.
- TJSL management.
- GCG category and document management.
- GCG highlight management.
- Investor document management.
- Investor highlight management.
- User management.
- Role and active status control.
- CMS content management with multilingual support.

---

### News Management

The news module supports:

- Featured image upload.
- Content image blocks.
- Bilingual translation.
- Auto-translate to English using background jobs.
- Writer and reviewer workflow.
- News preview.
- Public news detail page.
- Category filter.
- Search filter.
- Year filter.
- Featured news.
- Latest news.
- Sitemap indexing.
- Audit log for editorial workflow.

News workflow:

```txt
Draft
    -> Submitted / In Review
    -> Approved or Rejected by Reviewer
    -> Published by Admin
    -> Archived if no longer active
```

---

### Writer Panel

Accessible through:

```txt
/writer
```

Reserved for users with the `writer` role.

Writer features:

- Writer dashboard.
- Create news article.
- Edit news article.
- Submit article for review.
- Manage content blocks.
- Upload featured image.
- Upload content images.
- Create and manage TJSL program entries.
- See reviewer feedback if content is rejected.

---

### Reviewer Panel

Accessible through:

```txt
/reviewer
```

Reserved for users with the `reviewer` role.

Reviewer features:

- Reviewer dashboard.
- Review submitted news.
- Preview content before publication.
- Approve or reject news.
- Review TJSL submissions.
- Provide notes or correction feedback.
- Read-only detail page for news and TJSL content.

---

### Operational Panel

Accessible through:

```txt
/operational
```

Reserved for users with the `operational` role.

Operational features:

- Operational dashboard.
- Flow gas daily records.
- Crude oil daily records.
- Vitol monthly records.
- Monthly and yearly chart visualization.
- Flow gas category summary.
- Crude oil last 14 days chart.
- Vitol last 12 records chart.
- Broadcast message management.
- Monthly flow gas Excel export.
- Fullscreen operational TV display.

---

### Operational TV Display

Accessible through:

```txt
/operational/tv
```

The TV display is designed for large screens and monitoring rooms.

TV display features:

- Fullscreen layout.
- Gas daily chart.
- Gas monthly chart.
- Gas yearly chart.
- Crude oil stacked chart.
- Vitol monthly chart based on the last 12 records.
- Broadcast ticker.
- Company profile video section.
- Month and year label.
- Optimized video loading and browser cache behavior.
- Public TV token support for display-only access.

For production, video files are stored under:

```txt
public_html/videos
```

Recommended video format:

```txt
MP4
H.264
AAC
720p or 1080p
Web Optimized / Fast Start enabled
```

---

### Whistleblowing System

The Whistleblowing System is accessible through:

```txt
/wbs
```

The module is separated into two major areas:

```txt
/wbs/pelapor
/wbs/admin
```

WBS supports confidential reporting for compliance-related cases.

#### Pelapor Features

Users with the `pelapor` role can:

- Register as a reporter.
- Verify email address.
- Login to WBS portal.
- Submit new report.
- Upload supporting attachments.
- Track report status.
- Edit report while status allows.
- Receive notification when report status is updated.
- View report history.
- Read admin response and follow-up result.

Report categories include:

```txt
Corruption
Bribery
Gratification
Conflict of Interest
Theft
Fraud
Legal or Regulatory Violation
Other Compliance Violation
```

Report lifecycle:

```txt
Laporan Masuk
    -> Ditelaah
    -> Perlu Klarifikasi
    -> Dalam Proses
    -> Dalam Investigasi
    -> Selesai
    -> Ditutup
    -> Di Luar Ruang Lingkup
```

#### WBS Admin / Officer Features

Users with `wbs_admin` or `wbs_officer` role can:

- View all reports.
- Filter reports by status, category, reporter, month, and year.
- Search reports by keyword.
- View report detail.
- Update report status.
- Add admin notes.
- Add follow-up result.
- Export individual report to PDF.
- Export filtered report list to PDF.
- Receive notification for new reports.
- Notify reporter when report status changes.

PDF exports are generated using DomPDF and saved under:

```txt
public_html/generated/wbs/reports
```

---

### Email Notifications

The system uses Laravel Mail for email notifications.

Email notification features:

- New WBS report notification to WBS admin.
- Report update notification to reporter.
- Forgot password email.
- Queue-based email sending.
- SMTP support for Titan Email or other mail providers.

Queue is used so email sending does not slow down user requests.

---

### Auto Translate News

News translation can be processed in the background using Laravel Queue.

The system can dispatch translation jobs after submitting or updating news content.

Main job example:

```txt
App\Jobs\TranslateNewsToEnglishJob
```

This job processes Indonesian news content and generates English translation data into `news_translations`.

---

### Sitemap and SEO

The application includes an XML sitemap route:

```txt
/sitemap.xml
```

The sitemap includes:

- Homepage `/id` and `/en`.
- Static public menu pages.
- CMS pages.
- Published news articles.
- Bilingual URL entries.
- Last modified date.
- Change frequency.
- Priority value.

Recommended robots file:

```txt
User-agent: *
Allow: /

Sitemap: https://bspz.co.id/sitemap.xml
```

Google Search Console can be used to submit:

```txt
https://bspz.co.id/sitemap.xml
```

---

## System Architecture

```txt
Public Website
    /id
    /en
    Homepage
    Profile
    News
    TJSL
    GCG
    Investor Relations
    Operational Public Page
    WBS Landing Page
    Legal Pages
    Sitemap

Admin Panel
    /admin
    Dashboard
    CMS
    News
    Pages
    Sliders
    Partners
    Menus
    Profile
    TJSL
    GCG
    Investor
    User Management

Writer Panel
    /writer
    News Authoring
    TJSL Authoring

Reviewer Panel
    /reviewer
    News Review
    TJSL Review
    Preview

Operational Panel
    /operational
    Dashboard
    Flow Gas
    Crude Oil
    Vitol
    Broadcast
    TV Display

WBS Panel
    /wbs
    Pelapor Portal
    WBS Admin Portal
    Reports
    Attachments
    Notifications
    PDF Export

Background Jobs
    Queue Email
    News Auto Translate
```

---

## User Roles

| Role | Access | Description |
|---|---|---|
| `admin` | `/admin` | Full CMS and system management |
| `operational` | `/operational` | Operational data input and monitoring |
| `writer` | `/writer` | News and TJSL authoring |
| `reviewer` | `/reviewer` | Content review and approval |
| `pelapor` | `/wbs/pelapor` | WBS report submission and tracking |
| `wbs_admin` | `/wbs/admin` | WBS report management |
| `wbs_officer` | `/wbs/admin` | WBS report handling and follow-up |

---

## Technology Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 |
| PHP Version | PHP 8.2+ |
| Frontend Build | Vite |
| CSS Framework | Tailwind CSS 4 |
| Database | MySQL / MariaDB |
| Authentication | Laravel Auth + Google OAuth |
| Queue | Laravel Queue Database Driver |
| Mail | Laravel Mail SMTP |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Export | maatwebsite/excel |
| Image Processing | intervention/image |
| PDF to Image | spatie/pdf-to-image |
| Charts | JavaScript chart library |
| Hosting | Shared Hosting / cPanel / Rumahweb |
| SEO | Sitemap XML + robots.txt + Google Search Console |

---

## Requirements

Minimum requirements:

```txt
PHP 8.2 or higher
Composer 2.x
Node.js 18 or higher
npm
MySQL 8.0 or MariaDB 10.6+
Apache or Nginx
cPanel compatible hosting
```

Required PHP extensions:

```txt
ext-gd
ext-mbstring
ext-pdo
ext-xml
ext-zip
ext-curl
ext-fileinfo
ext-openssl
```

Recommended PHP settings:

```ini
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 180
memory_limit = 256M
```

---

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/hidayaturromadhan/bsp-zapin.git
cd bsp-zapin
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Configure Database

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bsp_zapin
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migration

```bash
php artisan migrate
```

### 7. Install Frontend Dependencies

```bash
npm install
```

### 8. Build Frontend Assets

```bash
npm run build
```

---

## Environment Configuration

Recommended `.env` configuration:

```env
APP_NAME="BSP Zapin"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=id
APP_FALLBACK_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bsp_zapin
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@example.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="BSP Zapin"

WBS_ADMIN_EMAIL=wbs@bspz.co.id

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

For production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bspz.co.id
QUEUE_CONNECTION=database
```

---

## Running the Application

### Development Server

```bash
php artisan serve
```

### Vite Development Server

```bash
npm run dev
```

### Queue Worker

```bash
php artisan queue:work
```

### Log Viewer

```bash
php artisan pail
```

### Production Build

```bash
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Production Deployment

This project is deployed on shared hosting with this structure:

```txt
/home/bspz7193/laravel       Laravel project files
/home/bspz7193/public_html   Public document root
```

Because the Laravel project is outside `public_html`, the application public path must be set to `public_html`.

In `bootstrap/app.php`:

```php
$app->usePublicPath('/home/bspz7193/public_html');
```

Recommended production commands:

```bash
cd /home/bspz7193/laravel

/usr/local/bin/ea-php82 artisan optimize:clear
/usr/local/bin/ea-php82 artisan config:clear
/usr/local/bin/ea-php82 artisan cache:clear
/usr/local/bin/ea-php82 artisan view:clear

/usr/local/bin/ea-php82 artisan config:cache
/usr/local/bin/ea-php82 artisan route:cache
/usr/local/bin/ea-php82 artisan view:cache
```

Required writable directories:

```txt
storage
bootstrap/cache
public_html/generated
public_html/uploads
public_html/images
public_html/videos
```

Permission setup:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

If shared hosting blocks write access, use carefully:

```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

---

## Queue and Cron Job

The application uses Laravel Queue with database driver.

Queue features:

- WBS email notification.
- WBS report update email.
- News auto translate job.
- Background processing for long-running tasks.

On shared hosting, queue is executed using Cron Job.

Example cPanel cron command:

```bash
cd /home/bspz7193/laravel && /usr/local/bin/ea-php82 artisan queue:work database --queue=default --stop-when-empty --tries=3 --timeout=180 >> /home/bspz7193/public_html/storage/logs/queue-cron.log 2>&1
```

Recommended cron interval:

```txt
Every minute
```

Equivalent cron schedule:

```txt
* * * * *
```

To check failed jobs:

```bash
php artisan queue:failed
```

To retry failed jobs:

```bash
php artisan queue:retry all
```

To clear failed jobs:

```bash
php artisan queue:flush
```

---

## SEO and Google Search Console

The project supports Google indexing through:

```txt
/sitemap.xml
/robots.txt
```

Recommended production robots file:

```txt
User-agent: *
Allow: /

Sitemap: https://bspz.co.id/sitemap.xml
```

Google Search Console setup:

1. Add property for domain:

```txt
bspz.co.id
```

2. Add TXT DNS verification record.
3. Wait for DNS propagation.
4. Submit sitemap:

```txt
sitemap.xml
```

5. Request indexing for important pages:

```txt
https://bspz.co.id/id
https://bspz.co.id/en
https://bspz.co.id/id/media-publikasi
https://bspz.co.id/id/wbs
https://bspz.co.id/id/gcg
```

DNS records required:

```txt
A      bspz.co.id       Hosting IP
CNAME  www              bspz.co.id
TXT    google-site-verification=...
TXT    SPF for email provider
```

---

## Module Documentation

### News Editorial Workflow

1. Writer creates news as draft.
2. Writer submits news to reviewer.
3. Reviewer approves or rejects.
4. Admin publishes approved news.
5. Published news appears on public website.
6. News may be archived when no longer active.
7. Translation job may generate English content automatically.
8. Sitemap includes published public news.

---

### WBS Report Lifecycle

WBS reports follow this lifecycle:

```txt
Laporan Masuk
    -> Ditelaah
    -> Perlu Klarifikasi
    -> Dalam Proses
    -> Dalam Investigasi
    -> Selesai
    -> Ditutup
    -> Di Luar Ruang Lingkup
```

Reporter can edit report only on allowed statuses:

```txt
Laporan Masuk
Perlu Klarifikasi
```

Admin can update:

```txt
Status
Admin Notes
Follow Up Result
Processed Date
Closed Date
```

PDF export includes:

```txt
Report number
Report status
Category
Title
Incident date
Location
Estimated loss
Description
Involved parties
Chronology
Reporter identity masked
Admin notes
Follow-up result
Attachments list
Signature block
Confidential watermark
```

---

### Operational Data

Operational module manages:

```txt
Flow Gas
Crude Oil
Vitol
Broadcast Message
Operational TV
```

Chart data includes:

```txt
Gas daily chart
Gas monthly average chart
Gas yearly chart
Crude last 14 days chart
Vitol last 12 records chart
```

---

### Upload Management

Uploads are handled using public path configuration.

Production upload target:

```txt
/home/bspz7193/public_html
```

Upload directories include:

```txt
images/news/featured
images/news/content
images/partners
uploads/wbs/reports
generated/wbs/reports
videos
```

Image processing uses:

```txt
intervention/image
```

---

### PDF Export

PDF export uses:

```txt
barryvdh/laravel-dompdf
```

PDF modules include:

```txt
Individual WBS report export
Filtered WBS report export
```

Recommended DomPDF production config:

```php
'public_path' => '/home/bspz7193/public_html',

'options' => [
    'font_dir' => storage_path('fonts'),
    'font_cache' => storage_path('fonts'),
    'temp_dir' => storage_path('framework/cache'),
    'chroot' => [
        base_path(),
        '/home/bspz7193/public_html',
        storage_path(),
    ],
    'enable_remote' => true,
]
```

---

## Security Notes

Important security features:

- Role-based access control.
- Login rate limiting.
- Active user check.
- Session middleware.
- Single-device session concept.
- Inactivity timeout.
- CSRF protection on forms.
- Server-side validation.
- WBS reporter data protection.
- Masked reporter identity in PDF.
- Upload validation.
- Queue-based email sending.
- Production debug disabled.
- Public directory separation.
- Sitemap only for public pages.

Production `.env` must use:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bspz.co.id
```

Sensitive files must not be publicly accessible:

```txt
.env
.git
composer.json
storage/logs
vendor
database
```

Recommended checks:

```bash
curl -I https://bspz.co.id/.env
curl -I https://bspz.co.id/.git/config
curl -I https://bspz.co.id/composer.json
curl -I https://bspz.co.id/storage/logs/laravel.log
```

Expected result:

```txt
403 Forbidden
404 Not Found
```

---

## Directory Structure

```txt
bsp-zapin/
|-- app/
|   |-- Helpers/
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- Admin/
|   |   |   |-- Auth/
|   |   |   |-- Operational/
|   |   |   |-- Reviewer/
|   |   |   |-- Web/
|   |   |   |-- Wbs/
|   |   |   |-- Writer/
|   |   |-- Middleware/
|   |-- Jobs/
|   |-- Mail/
|   |-- Models/
|   |-- Providers/
|   |-- Services/
|-- bootstrap/
|   |-- app.php
|-- config/
|   |-- app.php
|   |-- dompdf.php
|   |-- queue.php
|   |-- services.php
|   |-- wbs.php
|-- database/
|   |-- migrations/
|   |-- seeders/
|-- public/
|   |-- images/
|   |-- videos/
|-- resources/
|   |-- views/
|   |   |-- admin/
|   |   |-- auth/
|   |   |-- operational/
|   |   |-- reviewer/
|   |   |-- web/
|   |   |-- wbs/
|   |   |-- writer/
|-- routes/
|   |-- web.php
|   |-- console.php
|-- storage/
|   |-- app/
|   |-- framework/
|   |-- logs/
|-- tests/
|-- composer.json
|-- package.json
|-- vite.config.js
```

---

## Database Schema

Main tables include:

| Table | Description |
|---|---|
| `users` | User accounts, role, active status, and session data |
| `sliders` | Homepage slider data |
| `partners` | Partner and customer logos |
| `menus` | Dynamic navigation menu |
| `pages` | Static CMS pages |
| `page_translations` | Page translation content |
| `content_versions` | CMS version history |
| `news_categories` | News categories |
| `news` | News article main records |
| `news_translations` | Bilingual news content |
| `news_images` | News image attachments |
| `news_audit_logs` | Editorial workflow logs |
| `tjsl_programs` | TJSL program records |
| `tjsl_program_translations` | TJSL translation content |
| `tjsl_program_images` | TJSL gallery images |
| `gcg_categories` | GCG document categories |
| `gcg_category_translations` | GCG category translations |
| `gcg_documents` | GCG document files |
| `gcg_document_translations` | GCG document translations |
| `gcg_highlight_items` | GCG highlight content |
| `investor_documents` | Investor document files |
| `investor_document_translations` | Investor document translations |
| `investor_highlight_items` | Investor highlight content |
| `flow_gas_categories` | Flow gas categories |
| `flow_gas_daily_records` | Daily gas records |
| `crude_daily_records` | Daily crude oil records |
| `vitol_records` | Monthly Vitol records |
| `broadcast_messages` | TV display ticker messages |
| `operational_display_tokens` | Public TV display tokens |
| `wbs_reports` | WBS report records |
| `wbs_report_attachments` | WBS report attachments |
| `wbs_notifications` | WBS notification records |
| `jobs` | Laravel queued jobs |
| `failed_jobs` | Failed queued jobs |
| `password_reset_tokens` | Password reset tokens |
| `sessions` | Session records |

---

## Maintenance Commands

Clear all cache:

```bash
php artisan optimize:clear
```

Rebuild cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Run migration:

```bash
php artisan migrate
```

Run queue:

```bash
php artisan queue:work
```

Check failed jobs:

```bash
php artisan queue:failed
```

Retry failed jobs:

```bash
php artisan queue:retry all
```

Build frontend:

```bash
npm run build
```

Run code formatter:

```bash
./vendor/bin/pint
```

---

## Common Production Issues

### Images not showing after upload

Make sure `public_path()` points to:

```txt
/home/bspz7193/public_html
```

Check:

```bash
php artisan tinker
public_path();
```

Expected output:

```txt
/home/bspz7193/public_html
```

---

### PDF export error 500

Check:

```bash
tail -n 100 storage/logs/laravel.log
```

Common fixes:

```bash
mkdir -p storage/fonts
mkdir -p storage/framework/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan optimize:clear
```

Make sure `config/dompdf.php` has correct `public_path`.

---

### Queue not processing

Check cron job:

```bash
* * * * * cd /home/bspz7193/laravel && /usr/local/bin/ea-php82 artisan queue:work database --queue=default --stop-when-empty --tries=3 --timeout=180 >> /home/bspz7193/public_html/storage/logs/queue-cron.log 2>&1
```

Check failed jobs:

```bash
php artisan queue:failed
```

---

### Google indexing not appearing

Make sure these are accessible:

```txt
https://bspz.co.id/sitemap.xml
https://bspz.co.id/robots.txt
```

Then submit sitemap in Google Search Console.

---

## Contributing

Recommended contribution workflow:

1. Fork repository.
2. Create feature branch.

```bash
git checkout -b feature/feature-name
```

3. Make changes.
4. Run formatter.

```bash
./vendor/bin/pint
```

5. Run tests if available.

```bash
php artisan test
```

6. Commit changes.

```bash
git commit -m "Add feature description"
```

7. Push branch.

```bash
git push origin feature/feature-name
```

8. Create pull request.

---

## License

This project is developed for PT Bumi Siak Pusako Zapin.

If this repository is used internally or for company-specific deployment, make sure all credentials, database dumps, `.env` files, private documents, and production assets are not committed to the repository.
